<?php

namespace Tests\Feature;

use Tests\TestCase;
use Carbon\CarbonImmutable;
use App\Dashboard\ScreenCondition;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class DashboardTest extends TestCase
{
    public function testItShowsTheWeekplanningScreenDuringTheConfiguredWindow(): void
    {
        config()->set('app.access_token', 'test-token');

        config()->set('dashboard.weekplanning', [
            'day_of_week' => 1,
            'start_time' => '12:00',
            'end_time' => '12:15',
            'timezone' => 'Europe/Brussels',
        ]);

        Http::fake();

        $this->travelTo(CarbonImmutable::parse('2026-04-13 12:05:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertSee('Weekplanning!');

        Http::assertNothingSent();
    }

    public function testItShowsTheRegularDashboardOutsideOfTheConfiguredWindow(): void
    {
        config()->set('app.access_token', 'test-token');

        config()->set('dashboard.weekplanning', [
            'day_of_week' => 1,
            'start_time' => '12:00',
            'end_time' => '12:15',
            'timezone' => 'Europe/Brussels',
        ]);

        config()->set('dashboard.screens', [
            'main' => [
                'view' => 'dashboard.screens.main',
            ],
        ]);
        config()->set('dashboard.schedule', [
            [
                'screen' => 'main',
                'duration_in_seconds' => 60,
            ],
        ]);

        Http::fake([
            'https://spatie.be/api/members' => Http::response([
                [
                    'name' => 'Tim',
                    'email' => 'tim@spatie.be',
                    'birthday' => '1989-05-18',
                ],
            ]),
        ]);

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertDontSee('Weekplanning!');

        Http::assertSentCount(1);
    }

    public function testItRendersAConfiguredBladeScreen(): void
    {
        config()->set('app.access_token', 'test-token');

        config()->set('dashboard.screens', [
            'main' => [
                'view' => 'dashboard.screens.main',
            ],
        ]);
        config()->set('dashboard.schedule', [
            [
                'screen' => 'main',
                'duration_in_seconds' => 45,
            ],
        ]);

        Http::fake([
            'https://spatie.be/api/members' => Http::response([
                [
                    'name' => 'Tim',
                    'email' => 'tim@spatie.be',
                    'birthday' => '1989-05-18',
                ],
            ]),
        ]);

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertSee('data-dashboard-screen', false)
            ->assertViewHas('schedule', fn (Collection $schedule): bool => $schedule->all() === [
                [
                    'screen' => 'main',
                    'duration_in_seconds' => 45,
                ],
            ])
            ->assertSee('team-member-tile', false);

        Http::assertSentCount(1);
    }

    public function testItRendersAConfiguredUrlScreen(): void
    {
        config()->set('app.access_token', 'test-token');

        config()->set('dashboard.screens', [
            'external-status' => [
                'url' => 'https://example.com/status',
            ],
        ]);
        config()->set('dashboard.schedule', [
            [
                'screen' => 'external-status',
                'duration_in_seconds' => 30,
            ],
        ]);

        Http::fake();

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertViewHas('schedule', fn (Collection $schedule): bool => $schedule->all() === [
                [
                    'screen' => 'external-status',
                    'duration_in_seconds' => 30,
                ],
            ])
            ->assertDontSee('grid gap-2 p-2 transition-opacity', false)
            ->assertSee('src="https://example.com/status"', false)
            ->assertSee('title="External Status"', false);

        Http::assertNothingSent();
    }

    public function testItUsesTheDefaultDurationForScheduleEntriesWithoutADuration(): void
    {
        config()->set('app.access_token', 'test-token');

        config()->set('dashboard.default_duration_in_seconds', 15);
        config()->set('dashboard.screens', [
            'external-status' => [
                'url' => 'https://example.com/status',
            ],
        ]);
        config()->set('dashboard.schedule', [
            [
                'screen' => 'external-status',
            ],
        ]);

        Http::fake();

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertViewHas('schedule', fn (Collection $schedule): bool => $schedule->all() === [
                [
                    'screen' => 'external-status',
                    'duration_in_seconds' => 15,
                ],
            ]);
    }

    public function testItSkipsUnknownScreensAndFallsBackToTheMainDashboard(): void
    {
        config()->set('app.access_token', 'test-token');

        config()->set('dashboard.screens', []);
        config()->set('dashboard.schedule', [
            [
                'screen' => 'unknown',
                'duration_in_seconds' => 30,
            ],
        ]);

        Http::fake([
            'https://spatie.be/api/members' => Http::response([
                [
                    'name' => 'Tim',
                    'email' => 'tim@spatie.be',
                    'birthday' => '1989-05-18',
                ],
            ]),
        ]);

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertViewHas('schedule', fn (Collection $schedule): bool => $schedule->all() === [
                [
                    'screen' => 'main',
                    'duration_in_seconds' => 60,
                ],
            ])
            ->assertSee('data-dashboard-screen', false)
            ->assertSee('team-member-tile', false);

        Http::assertSentCount(1);
    }

    public function testItRendersTheFourProductScreensInOrderWithoutLoadingFathom(): void
    {
        config()->set('app.access_token', 'test-token');
        $productScreens = collect([
            ['mailcoach', 'Mailcoach', '📯', 'GSENXMLW'],
            ['flare', 'Flare', '🎆', 'LBABKDJB'],
            ['spatie', 'Spatie', '🔵', 'OMNDKUTR'],
            ['there-there', 'There There', '🎫', 'UJQKGGUH'],
        ])->mapWithKeys(fn (array $product): array => [
            $product[0] => [
                'view' => 'dashboard.screens.productAnalytics',
                'product' => [
                    'name' => $product[1],
                    'emoji' => $product[2],
                    'site_id' => $product[3],
                ],
            ],
        ])->all();

        config()->set('dashboard.screens', [
            'main' => [
                'view' => 'dashboard.screens.main',
            ],
            ...$productScreens,
            'now-playing' => [
                'url' => 'https://liveat.spatie.be/now-playing',
            ],
        ]);
        config()->set('dashboard.schedule', collect([
            ['main', 90],
            ['mailcoach', 60],
            ['flare', 60],
            ['spatie', 60],
            ['there-there', 60],
            ['now-playing', 300],
        ])->map(fn (array $scheduleEntry): array => [
            'screen' => $scheduleEntry[0],
            'duration_in_seconds' => $scheduleEntry[1],
        ])->all());

        Http::fake([
            'https://spatie.be/api/members' => Http::response([]),
        ]);

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $response = $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertDontSee('fathom-tile', false)
            ->assertDontSee('dashboard:fetch-fathom-statistics')
            ->assertSeeInOrder([
                'data-screen-name="main"',
                'data-screen-name="mailcoach"',
                'data-screen-name="flare"',
                'data-screen-name="spatie"',
                'data-screen-name="there-there"',
                'data-screen-name="now-playing"',
            ], false)
            ->assertSee('data-product-analytics-screen', false)
            ->assertSee("document.addEventListener('livewire:initialized'", false);

        $this->assertSame(4, substr_count($response->getContent(), 'data-product-analytics-component='));
        Http::assertSentCount(1);
    }

    public function testItCanScheduleTheSameScreenMoreThanOnceWithoutRenderingItMoreThanOnce(): void
    {
        config()->set('app.access_token', 'test-token');
        config()->set('dashboard.screens', [
            'mailcoach' => [
                'view' => 'dashboard.screens.productAnalytics',
                'product' => [
                    'name' => 'Mailcoach',
                    'emoji' => '📯',
                    'site_id' => 'GSENXMLW',
                ],
            ],
        ]);
        config()->set('dashboard.schedule', [
            [
                'screen' => 'mailcoach',
                'duration_in_seconds' => 30,
            ],
            [
                'screen' => 'mailcoach',
                'duration_in_seconds' => 90,
            ],
        ]);

        Http::fake();

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $response = $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertViewHas('schedule', fn (Collection $schedule): bool => $schedule->all() === [
                [
                    'screen' => 'mailcoach',
                    'duration_in_seconds' => 30,
                ],
                [
                    'screen' => 'mailcoach',
                    'duration_in_seconds' => 90,
                ],
            ]);

        $this->assertSame(1, substr_count($response->getContent(), 'data-screen-name="mailcoach"'));
        $this->assertSame(1, substr_count($response->getContent(), 'data-product-analytics-component='));
        Http::assertNothingSent();
    }

    public function testItInitiallySkipsAConditionalScreenThatIsUnavailable(): void
    {
        config()->set('app.access_token', 'test-token');
        config()->set('dashboard.screens', [
            'conditional' => [
                'url' => 'https://example.com/conditional',
                'condition' => NeverDisplayScreenCondition::class,
            ],
            'main' => [
                'view' => 'dashboard.screens.main',
            ],
        ]);
        config()->set('dashboard.schedule', [
            [
                'screen' => 'conditional',
                'duration_in_seconds' => 30,
            ],
            [
                'screen' => 'main',
                'duration_in_seconds' => 60,
            ],
        ]);

        Http::fake([
            'https://spatie.be/api/members' => Http::response([]),
        ]);

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertViewHas('availableScreenNames', ['main'])
            ->assertViewHas('initialScreenName', 'main')
            ->assertViewHas('hasConditionalScreens', true)
            ->assertSee('wire:poll.10s', false)
            ->assertSee('data-screen-name="conditional"', false)
            ->assertSee('data-screen-name="main"', false);

        Http::assertSentCount(1);
    }
}

class NeverDisplayScreenCondition implements ScreenCondition
{
    public function shouldDisplay(): bool
    {
        return false;
    }
}
