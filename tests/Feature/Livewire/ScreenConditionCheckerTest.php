<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Dashboard\ScreenCondition;
use App\Livewire\ScreenConditionCheckerComponent;

class ScreenConditionCheckerTest extends TestCase
{
    protected function tearDown(): void
    {
        ToggleableScreenCondition::$shouldDisplay = false;

        parent::tearDown();
    }

    public function testItBroadcastsChangesToScreenAvailability(): void
    {
        config()->set('dashboard.screens', [
            'conditional' => [
                'url' => 'https://example.com/conditional',
                'condition' => ToggleableScreenCondition::class,
            ],
            'main' => [
                'view' => 'dashboard.screens.main',
            ],
        ]);

        $component = Livewire::test(ScreenConditionCheckerComponent::class, [
            'screenNames' => ['conditional', 'main'],
        ])->assertDispatched(
            'dashboard-screen-availability-updated',
            availableScreenNames: ['main'],
        );

        ToggleableScreenCondition::$shouldDisplay = true;

        $component
            ->call('$refresh')
            ->assertDispatched(
                'dashboard-screen-availability-updated',
                availableScreenNames: ['conditional', 'main'],
            );
    }
}

class ToggleableScreenCondition implements ScreenCondition
{
    public static bool $shouldDisplay = false;

    public function shouldDisplay(): bool
    {
        return self::$shouldDisplay;
    }
}
