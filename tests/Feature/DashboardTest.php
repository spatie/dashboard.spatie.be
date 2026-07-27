<?php

namespace Tests\Feature;

use Tests\TestCase;
use Carbon\CarbonImmutable;
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
            'default_duration_in_seconds' => 60,
            'items' => [
                [
                    'name' => 'main',
                    'view' => 'dashboard.screens.main',
                    'duration_in_seconds' => 60,
                ],
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
            'default_duration_in_seconds' => 60,
            'items' => [
                [
                    'name' => 'main',
                    'view' => 'dashboard.screens.main',
                    'duration_in_seconds' => 45,
                ],
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
            ->assertSee('data-duration-in-seconds="45"', false)
            ->assertSee('team-member-tile', false);

        Http::assertSentCount(1);
    }

    public function testItRendersAConfiguredUrlScreen(): void
    {
        config()->set('app.access_token', 'test-token');

        config()->set('dashboard.screens', [
            'default_duration_in_seconds' => 60,
            'items' => [
                [
                    'name' => 'external status',
                    'url' => 'https://example.com/status',
                    'duration_in_seconds' => 30,
                ],
            ],
        ]);

        Http::fake();

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertSee('data-duration-in-seconds="30"', false)
            ->assertDontSee('grid gap-2 p-2 transition-opacity', false)
            ->assertSee('src="https://example.com/status"', false)
            ->assertSee('title="external status"', false);

        Http::assertNothingSent();
    }

    public function testItUsesTheDefaultDurationForScreensWithoutADuration(): void
    {
        config()->set('app.access_token', 'test-token');

        config()->set('dashboard.screens', [
            'default_duration_in_seconds' => 15,
            'items' => [
                [
                    'name' => 'external status',
                    'url' => 'https://example.com/status',
                ],
            ],
        ]);

        Http::fake();

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertSee('data-duration-in-seconds="15"', false);
    }

    public function testItFallsBackToTheMainDashboardWhenNoScreensAreConfigured(): void
    {
        config()->set('app.access_token', 'test-token');

        config()->set('dashboard.screens.items', []);

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
            ->assertSee('team-member-tile', false);

        Http::assertSentCount(1);
    }

    public function testItRendersTheFourProductScreensInOrderWithoutLoadingFathom(): void
    {
        config()->set('app.access_token', 'test-token');

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
                'data-screen-name="now playing"',
            ], false)
            ->assertSee('data-product-analytics-screen', false);

        $this->assertSame(4, substr_count($response->getContent(), 'data-product-analytics-component='));
        Http::assertSentCount(1);
    }
}
