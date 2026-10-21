<?php

namespace Tests\Feature\Tiles\OhDear;

use App\Models\OhDearMessage;
use App\Tiles\OhDear\OhDearMessagesTileComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class OhDearMessagesTileComponentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

        $this->travelTo(now()->startOfSecond());
    }

    public function testItGroupsRecentMessagesUsingTheFirstEligibleMessagesFieldsAndLatestTime(): void
    {
        $this->createMessage([
            'occurred_at' => now()->subDay()->subSecond(),
            'severity' => 'error',
            'title' => 'Expired title',
        ]);
        $this->createMessage([
            'occurred_at' => now()->subHours(12),
            'severity' => 'warning',
            'title' => 'Original title',
            'site' => null,
        ]);
        $this->createMessage([
            'occurred_at' => now()->subDay(),
            'title' => 'Boundary title',
        ]);
        $this->createMessage([
            'occurred_at' => now()->subHour(),
            'severity' => 'error',
            'title' => 'Latest title',
            'site' => 'https://other.example.com',
        ]);
        $this->createMessage([
            'group_key' => 'expired-group',
            'occurred_at' => now()->subDays(2),
        ]);

        $groups = (new OhDearMessagesTileComponent)->render()->getData()['groups'];

        $this->assertCount(1, $groups);
        $this->assertSame('warning', $groups[0]['severity']);
        $this->assertSame('Original title', $groups[0]['title']);
        $this->assertNull($groups[0]['site']);
        $this->assertSame(3, $groups[0]['count']);
        $this->assertEquals(now()->subHour(), $groups[0]['latest_at']);
    }

    public function testItOrdersErrorsBeforeWarningsThenByCountAndFirstMessage(): void
    {
        foreach ([
            ['warning-many', 'warning', 4],
            ['error-first', 'error', 2],
            ['error-second', 'error', 2],
            ['error-single', 'error', 1],
            ['warning-single', 'warning', 1],
        ] as [$groupKey, $severity, $count]) {
            for ($index = 0; $index < $count; $index++) {
                $this->createMessage([
                    'group_key' => $groupKey,
                    'severity' => $severity,
                    'title' => $groupKey,
                    'site' => 'https://spatie.be',
                ]);
            }
        }

        $groups = (new OhDearMessagesTileComponent)->render()->getData()['groups'];

        $this->assertSame([
            'error-first',
            'error-second',
            'error-single',
            'warning-many',
            'warning-single',
        ], $groups->pluck('title')->all());
        $this->assertSame([2, 2, 1, 4, 1], $groups->pluck('count')->all());
        $this->assertSame('https://spatie.be', $groups[0]['site']);

        Livewire::test(OhDearMessagesTileComponent::class, ['position' => 'a1'])
            ->assertSeeInOrder($groups->pluck('title')->all());
    }

    public function testItRendersTheEmptyStateWithoutRecentMessages(): void
    {
        $this->createMessage(['occurred_at' => now()->subDay()->subSecond()]);

        Livewire::test(OhDearMessagesTileComponent::class, ['position' => 'a1'])
            ->assertViewHas('groups', fn ($groups) => $groups->isEmpty())
            ->assertSee('No alerts in the last 24h');
    }

    /** @param array<string, mixed> $attributes */
    private function createMessage(array $attributes = []): OhDearMessage
    {
        return OhDearMessage::create([
            'event_type' => 'cronFailedNotification',
            'group_key' => 'cron-alert',
            'severity' => 'warning',
            'title' => 'Cron failed',
            'site' => 'https://spatie.be',
            'payload' => ['details' => 'Full webhook payload'],
            'occurred_at' => now(),
            ...$attributes,
        ]);
    }
}
