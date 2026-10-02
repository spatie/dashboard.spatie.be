<?php

namespace Tests\Feature;

use App\Models\OhDearMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Spatie\Dashboard\Models\Tile;
use Tests\TestCase;

class OhDearWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('webhook-client.configs.1.signing_secret', 'oh-dear-secret');
    }

    public function testAFailedUptimeCheckMarksTheSiteAsDown(): void
    {
        $this->postOhDearWebhook($this->uptimeCheckFailedPayload())->assertOk();

        $this->assertSame(['https://vulpia.be'], $this->downSites());
    }

    public function testARecoveredUptimeCheckMarksTheSiteAsUp(): void
    {
        $this->postOhDearWebhook($this->uptimeCheckFailedPayload())->assertOk();
        $this->postOhDearWebhook($this->uptimeCheckFailedPayload('https://spatie.be'))->assertOk();

        $this->postOhDearWebhook($this->uptimeCheckRecoveredPayload())->assertOk();

        $this->assertSame(['https://spatie.be'], $this->downSites());
    }

    public function testLegacyUptimeEventNamesUpdateTheUptimeTile(): void
    {
        $this->postOhDearWebhook([
            ...$this->uptimeCheckFailedPayload(),
            'type' => 'uptimeCheckFailedNotification',
        ])->assertOk();

        $this->assertSame(['https://vulpia.be'], $this->downSites());

        $this->postOhDearWebhook([
            ...$this->uptimeCheckRecoveredPayload(),
            'type' => 'uptimeCheckRecoveredNotification',
        ])->assertOk();

        $this->assertSame([], $this->downSites());
    }

    public function testAFailedUptimeCheckIsStoredWithTheMonitorUrlAsSite(): void
    {
        $this->postOhDearWebhook($this->uptimeCheckFailedPayload())->assertOk();

        $message = OhDearMessage::sole();

        $this->assertSame('https://vulpia.be', $message->site);
        $this->assertSame('uptime', $message->check_type);
        $this->assertSame('error', $message->severity);
    }

    public function testARecoveredUptimeCheckClearsTheFailedMessage(): void
    {
        $this->postOhDearWebhook($this->uptimeCheckFailedPayload())->assertOk();

        $this->assertSame(1, OhDearMessage::query()->where('severity', 'error')->count());

        $this->postOhDearWebhook($this->uptimeCheckRecoveredPayload())->assertOk();

        $this->assertSame(0, OhDearMessage::query()->where('severity', 'error')->count());
    }

    public function testWebhooksWithAnInvalidSignatureAreRejected(): void
    {
        $this
            ->postJson('/webhooks/oh-dear', $this->uptimeCheckFailedPayload(), ['OhDear-Signature' => 'invalid'])
            ->assertStatus(500);

        $this->assertSame([], $this->downSites());
    }

    private function postOhDearWebhook(array $payload): TestResponse
    {
        $body = json_encode($payload);

        return $this->call(
            'POST',
            '/webhooks/oh-dear',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_OHDEAR_SIGNATURE' => hash_hmac('sha256', $body, 'oh-dear-secret'),
            ],
            content: $body,
        );
    }

    private function downSites(): array
    {
        $tile = Tile::query()->firstWhere('name', 'ohDearUptime');

        return array_values($tile?->getData('downSites') ?? []);
    }

    private function uptimeCheckFailedPayload(string $url = 'https://vulpia.be'): array
    {
        return [
            'type' => 'httpUptimeCheckFailedNotification',
            'dateTime' => '20261002120000',
            'run' => [
                'id' => 1,
                'check' => [
                    'id' => 2,
                    'type' => 'uptime',
                    'label' => 'Uptime',
                    'latest_completed_run_summary' => 'Could not connect',
                    'monitor' => [
                        'id' => 3,
                        'url' => $url,
                        'sort_url' => str_replace('https://', '', $url),
                        'label' => str_replace('https://', '', $url),
                    ],
                ],
            ],
        ];
    }

    private function uptimeCheckRecoveredPayload(string $url = 'https://vulpia.be'): array
    {
        return [
            'type' => 'httpUptimeCheckRecoveredNotification',
            'dateTime' => '20261002121000',
            'site' => [
                'id' => 3,
                'url' => $url,
                'sort_url' => str_replace('https://', '', $url),
                'label' => str_replace('https://', '', $url),
            ],
            'run' => [
                'id' => 4,
                'check' => [
                    'id' => 2,
                    'type' => 'uptime',
                    'label' => 'Uptime',
                    'latest_completed_run_summary' => 'Up',
                    'monitor' => [
                        'id' => 3,
                        'url' => $url,
                        'sort_url' => str_replace('https://', '', $url),
                        'label' => str_replace('https://', '', $url),
                    ],
                ],
            ],
        ];
    }
}
