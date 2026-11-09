<?php

namespace Tests\Feature\Tiles\OhDear;

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

    #[DataProvider('invalidPages')]
    public function testAnIncompleteRefreshPreservesThePreviousList(mixed $body, int $status): void
    {
        Http::fake([
            'https://ohdear.app/api/monitors*' => Http::sequence()
                ->push(self::page([self::monitor('https://new-outage.example')], 1, 2))
                ->push($body, $status),
        ]);

        $this->artisan('dashboard:fetch-oh-dear-uptime')->assertFailed();

        $this->assertSame(['https://recovered.example'], $this->downSites());
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
