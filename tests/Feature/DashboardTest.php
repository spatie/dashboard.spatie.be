<?php

namespace Tests\Feature;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

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
}
