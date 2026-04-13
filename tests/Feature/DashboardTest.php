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
            'refresh_interval_in_seconds' => 15,
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
            'refresh_interval_in_seconds' => 15,
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
}
