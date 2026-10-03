<?php

namespace Tests\Feature;

use App\Models\OhDearMessage;
use DateTimeInterface;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\WebhookClient\Models\WebhookCall;
use Tests\TestCase;

class PruneStaleWebhookDataTest extends TestCase
{
    use RefreshDatabase;

    public function testItPrunesWebhookCallsOlderThanSevenDays(): void
    {
        $staleWebhookCall = $this->createWebhookCall(now()->subDays(8));
        $recentWebhookCall = $this->createWebhookCall(now()->subDays(6));

        $this->artisan('model:prune', ['--model' => [WebhookCall::class]])->assertSuccessful();

        $this->assertModelMissing($staleWebhookCall);
        $this->assertModelExists($recentWebhookCall);
    }

    public function testItPrunesOhDearMessagesOlderThanSevenDays(): void
    {
        $staleMessage = $this->createOhDearMessage(now()->subDays(8));
        $recentMessage = $this->createOhDearMessage(now()->subDays(6));

        $this->artisan('model:prune', ['--model' => [OhDearMessage::class]])->assertSuccessful();

        $this->assertModelMissing($staleMessage);
        $this->assertModelExists($recentMessage);
    }

    public function testPruningIsScheduledEveryWeekdayMorning(): void
    {
        $pruneEvent = collect(app(Schedule::class)->events())
            ->first(fn ($event) => str_contains($event->command, 'model:prune'));

        $this->assertNotNull($pruneEvent);
        $this->assertSame('5 4 * * 1-5', $pruneEvent->expression);
        $this->assertStringContainsString('WebhookCall', $pruneEvent->command);
        $this->assertStringContainsString('OhDearMessage', $pruneEvent->command);
    }

    private function createWebhookCall(DateTimeInterface $createdAt): WebhookCall
    {
        $webhookCall = WebhookCall::create([
            'name' => 'oh-dear',
            'url' => 'https://dashboard.spatie.be/webhooks/oh-dear',
            'payload' => ['type' => 'cronFailedNotification'],
        ]);

        $webhookCall->forceFill(['created_at' => $createdAt])->save();

        return $webhookCall;
    }

    private function createOhDearMessage(DateTimeInterface $occurredAt): OhDearMessage
    {
        return OhDearMessage::create([
            'event_type' => 'cronFailedNotification',
            'severity' => 'error',
            'group_key' => sha1('cronFailedNotification'),
            'check_type' => 'cron',
            'title' => 'Cron failed',
            'site' => 'https://spatie.be',
            'payload' => [],
            'occurred_at' => $occurredAt,
        ]);
    }
}
