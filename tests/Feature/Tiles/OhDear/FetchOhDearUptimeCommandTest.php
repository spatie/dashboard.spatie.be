<?php

namespace Tests\Feature\Tiles\OhDear;

use App\Models\OhDearMessage;
use App\Tiles\OhDear\OhDearMessagesTileComponent;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Dashboard\Models\Tile;
use Spatie\OhDearUptimeTile\OhDearUptimeTileComponent;
use Tests\TestCase;

class FetchOhDearUptimeCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.oh_dear.token', 'test-oh-dear-token');
        Http::preventStrayRequests();
        Tile::firstOrCreateForName('ohDearUptime')->putData('downSites', ['https://recovered.example']);
    }

    public function testItReplacesStaleDowntimeWithEnabledFailedUptimeChecksFromEveryPage(): void
    {
        Http::fake([
            'https://ohdear.app/api/monitors*' => function (Request $request): PromiseInterface {
                $this->assertTrue($request->hasHeader('Authorization', 'Bearer test-oh-dear-token'));
                $this->assertTrue($request->hasHeader('Accept', 'application/json'));

                return match ((int) $request['page']['number']) {
                    1 => Http::response(self::page([
                        self::monitor('https://down-a.example'),
                        self::monitor('https://recovered.example', 'succeeded'),
                        self::monitor('https://disabled.example', enabled: false),
                        self::monitor('https://broken-links.example', type: 'broken_links'),
                        self::monitor('https://warning.example', 'warning'),
                        self::monitor('https://pending.example', 'pending'),
                        self::monitor('https://check-error.example', 'errored-or-timed-out'),
                    ], 1, 2)),
                    2 => Http::response(self::page([
                        self::monitor('https://down-b.example'),
                        self::monitor('https://down-a.example'),
                    ], 2, 2)),
                };
            },
        ]);

        $this->artisan('dashboard:fetch-oh-dear-uptime')->assertSuccessful();

        $this->assertSame(['https://down-a.example', 'https://down-b.example'], $this->downSites());

        Livewire::test(OhDearUptimeTileComponent::class, ['position' => 'e7:e16'])
            ->assertSee('https://down-a.example')
            ->assertSee('https://down-b.example')
            ->assertDontSee('https://recovered.example')
            ->assertDontSee('https://broken-links.example');
    }

    public function testAnEmptyAccountClearsOldDowntime(): void
    {
        Http::fake(['https://ohdear.app/api/monitors*' => Http::response(self::page([]))]);

        $this->artisan('dashboard:fetch-oh-dear-uptime')->assertSuccessful();

        $this->assertSame([], $this->downSites());
    }

    public function testItClearsOnlyCronAlertsCoveredByASuccessfulEnabledCheck(): void
    {
        $this->travelTo(now()->setDate(2026, 11, 9)->setTime(14, 15));

        $alerts = collect([
            ['recovered', 'cron', '14:09:59'],
            ['recovered', 'cron', '14:10:00'],
            ['recovered', 'cron', '14:10:01'],
            ['recovered', 'dns', '14:09:59'],
            ['failed', 'cron', '14:09:59'],
            ['warning', 'cron', '14:09:59'],
            ['pending', 'cron', '14:09:59'],
            ['check-error', 'cron', '14:09:59'],
            ['disabled', 'cron', '14:09:59'],
            ['undated', 'cron', '14:09:59'],
            ['missing', 'cron', '14:09:59'],
        ])->map(fn (array $alert) => OhDearMessage::create([
            'event_type' => $alert[1] === 'cron' ? 'cronNotExecutedOnTimeNotification' : 'dnsRecordsChangedNotification',
            'severity' => 'warning',
            'group_key' => implode('|', $alert),
            'check_type' => $alert[1],
            'title' => implode(' ', $alert),
            'site' => "https://{$alert[0]}.example",
            'payload' => [],
            'occurred_at' => "2026-11-09 {$alert[2]}",
        ]));

        $monitors = collect([
            ['recovered', 'succeeded', true],
            ['failed', 'failed', true],
            ['warning', 'warning', true],
            ['pending', 'pending', true],
            ['check-error', 'errored-or-timed-out', true],
            ['disabled', 'succeeded', false],
        ])->map(function (array $monitor) {
            $data = self::monitor("https://{$monitor[0]}.example", $monitor[1], $monitor[2], 'cron');
            $data['checks'][0]['latest_run_ended_at'] = '2026-11-09 13:10:00';

            return $data;
        })->all();

        Http::fake([
            'https://ohdear.app/api/monitors*' => Http::sequence()
                ->push(self::page([self::monitor('https://undated.example', 'succeeded', type: 'cron')], 1, 2))
                ->push(self::page($monitors, 2, 2)),
        ]);

        $this->artisan('dashboard:fetch-oh-dear-uptime')->assertSuccessful();

        $this->assertSame($alerts->slice(2)->pluck('id')->all(), OhDearMessage::query()->orderBy('id')->pluck('id')->all());

        Livewire::test(OhDearMessagesTileComponent::class, ['position' => 'c16:d20'])
            ->assertDontSee('recovered cron 14:09:59')
            ->assertDontSee('recovered cron 14:10:00')
            ->assertSee('recovered cron 14:10:01')
            ->assertSee('recovered dns 14:09:59')
            ->assertSee('failed cron 14:09:59');
    }

    #[DataProvider('invalidPages')]
    public function testAnIncompleteRefreshPreservesThePreviousList(mixed $body, int $status): void
    {
        $alert = OhDearMessage::create([
            'event_type' => 'cronNotExecutedOnTimeNotification',
            'severity' => 'warning',
            'group_key' => 'cron-alert',
            'check_type' => 'cron',
            'title' => 'Scheduled task issues detected',
            'site' => 'https://recovered.example',
            'payload' => [],
            'occurred_at' => now()->subHour(),
        ]);
        $recoveredMonitor = self::monitor('https://recovered.example', 'succeeded', type: 'cron');
        $recoveredMonitor['checks'][0]['latest_run_ended_at'] = now()->utc()->toDateTimeString();

        Http::fake([
            'https://ohdear.app/api/monitors*' => Http::sequence()
                ->push(self::page([self::monitor('https://new-outage.example'), $recoveredMonitor], 1, 2))
                ->push($body, $status),
        ]);

        $this->artisan('dashboard:fetch-oh-dear-uptime')->assertFailed();

        $this->assertSame(['https://recovered.example'], $this->downSites());
        $this->assertModelExists($alert);
    }

    public static function invalidPages(): array
    {
        return [
            'unauthorized' => [[], 401],
            'rate limited' => [[], 429],
            'server error' => [[], 500],
            'invalid JSON' => ['not JSON', 200],
            'missing pagination' => [['data' => []], 200],
            'repeated page' => [self::page([], 1, 2), 200],
            'missing monitors' => [['meta' => ['current_page' => 2, 'last_page' => 2]], 200],
            'missing checks' => [self::page([['url' => 'https://invalid.example']], 2, 2), 200],
            'invalid check result' => [self::page([self::monitor('https://invalid.example', 'unknown')], 2, 2), 200],
        ];
    }

    public function testAConnectionFailurePreservesThePreviousList(): void
    {
        Http::fake(['https://ohdear.app/api/monitors*' => Http::failedConnection()]);

        $this->artisan('dashboard:fetch-oh-dear-uptime')->assertFailed();

        $this->assertSame(['https://recovered.example'], $this->downSites());
    }

    public function testAMissingTokenPreservesThePreviousListWithoutCallingTheApi(): void
    {
        config()->set('services.oh_dear.token', null);
        Http::fake();

        $this->artisan('dashboard:fetch-oh-dear-uptime')
            ->expectsOutput('No Oh Dear API token is configured.')
            ->assertFailed();

        $this->assertSame(['https://recovered.example'], $this->downSites());
        Http::assertNothingSent();
    }

    /** @return array<int, string> */
    private function downSites(): array
    {
        return Tile::query()->firstWhere('name', 'ohDearUptime')->getData('downSites');
    }

    private static function monitor(string $url, string $result = 'failed', bool $enabled = true, string $type = 'uptime'): array
    {
        return [
            'url' => $url,
            'summarized_check_result' => 'failed',
            'checks' => [[
                'type' => $type,
                'enabled' => $enabled,
                'latest_run_result' => $result,
            ]],
        ];
    }

    private static function page(array $monitors, int $page = 1, int $lastPage = 1): array
    {
        return [
            'data' => $monitors,
            'meta' => ['current_page' => $page, 'last_page' => $lastPage],
        ];
    }
}
