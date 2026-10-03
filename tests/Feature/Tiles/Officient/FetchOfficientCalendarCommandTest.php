<?php

namespace Tests\Feature\Tiles\Officient;

use Tests\TestCase;
use App\Services\Officient\Exceptions\RateLimitExceeded;
use App\Services\Officient\Officient;
use App\Tiles\Officient\OfficientStore;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Log;
use Mockery;
use RuntimeException;

class FetchOfficientCalendarCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testItExcludesPeopleWithNoScheduledMinutesOrTimeOffEvents(): void
    {
        $this->travelTo(Carbon::parse('2026-08-13 10:00', 'Europe/Brussels'));

        $officient = Mockery::mock(Officient::class);

        $officient->shouldReceive('getPeople')
            ->once()
            ->andReturn(collect([
                ['id' => 1, 'name' => 'Nico', 'email' => 'nico@example.com'],
                ['id' => 2, 'name' => 'Marceli', 'email' => 'marceli@example.com'],
                ['id' => 3, 'name' => 'Freek', 'email' => 'freek@example.com'],
            ]));

        $officient->shouldReceive('getPersonDetail')
            ->times(3)
            ->andReturnUsing(fn (int $personId) => [
                'avatar' => "https://example.com/{$personId}.jpg",
                'employment' => [
                    'first_employment_date' => '2020-01-01',
                    'last_employment_date' => null,
                ],
            ]);

        $officient->shouldReceive('getDayCalendar')
            ->andReturnUsing(fn (int $personId, Carbon $date) => [
                'company_days_off' => [],
                'time_off' => [[
                    'date' => $date->toDateString(),
                    'scheduled_minutes' => $personId === 1 ? 0 : 456,
                    'events' => $personId === 2
                        ? [[
                            'name' => 'Toegestane afwezigheid',
                            'event_type' => 'custom',
                        ]]
                        : [],
                ]],
            ]);

        $this->app->instance(Officient::class, $officient);

        $this->artisan('dashboard:fetch-officient-calendar')->assertSuccessful();

        foreach (OfficientStore::make()->week() as $day) {
            $this->assertSame([
                [
                    'name' => 'Freek',
                    'avatar' => 'https://example.com/3.jpg',
                ],
            ], $day['in_office']);
        }
    }

    public function testAFailingCompanyDaysOffRequestDoesNotAbortTheRun(): void
    {
        $this->travelTo(Carbon::parse('2026-08-13 10:00', 'Europe/Brussels'));

        $officient = $this->mockOfficientWithPeople();

        $officient->shouldReceive('getDayCalendar')
            ->once()
            ->andThrow($this->connectionFailure());

        $officient->shouldReceive('getDayCalendar')
            ->andReturn([
                'company_days_off' => [],
                'time_off' => [],
            ]);

        $this->artisan('dashboard:fetch-officient-calendar')->assertSuccessful();

        $week = OfficientStore::make()->week();

        $this->assertCount(5, $week);

        foreach ($week as $day) {
            $this->assertSame([[
                'name' => 'Freek',
                'avatar' => 'https://example.com/1.jpg',
            ]], $day['in_office']);
        }
    }

    public function testItKeepsTheStoredWeekWhenAllCalendarRequestsFail(): void
    {
        $this->travelTo(Carbon::parse('2026-08-13 10:00', 'Europe/Brussels'));

        $storedWeek = [['date' => '2026-08-10', 'in_office' => [['name' => 'Freek']]]];

        OfficientStore::make()->setWeek($storedWeek);

        $officient = $this->mockOfficientWithPeople();

        $officient->shouldReceive('getDayCalendar')
            ->andThrow($this->connectionFailure());

        $this->artisan('dashboard:fetch-officient-calendar')->assertSuccessful();

        $this->assertSame($storedWeek, OfficientStore::make()->week());
    }

    public function testItKeepsTheStoredWeekWhenACalendarRequestIsRateLimited(): void
    {
        $this->travelTo(Carbon::parse('2026-08-13 10:00', 'Europe/Brussels'));

        Exceptions::fake();
        Log::spy();

        $storedWeek = [['date' => '2026-08-10', 'in_office' => [['name' => 'Freek']]]];

        OfficientStore::make()->setWeek($storedWeek);

        $officient = $this->mockOfficientWithPeople();

        $officient->shouldReceive('getDayCalendar')
            ->once()
            ->andReturn(['company_days_off' => [], 'time_off' => []]);

        $officient->shouldReceive('getDayCalendar')
            ->andThrow($this->rateLimitExceeded());

        $this->artisan('dashboard:fetch-officient-calendar')->assertSuccessful();

        $this->assertSame($storedWeek, OfficientStore::make()->week());

        Exceptions::assertNothingReported();

        Log::shouldHaveReceived('warning')
            ->withArgs(fn (string $message) => str_contains($message, 'Officient is rate limiting the dashboard'))
            ->once();
    }

    public function testItKeepsTheStoredWeekWhenFetchingThePeopleIsRateLimited(): void
    {
        $this->travelTo(Carbon::parse('2026-08-13 10:00', 'Europe/Brussels'));

        Exceptions::fake();
        Log::spy();

        $storedWeek = [['date' => '2026-08-10', 'in_office' => [['name' => 'Freek']]]];

        OfficientStore::make()->setWeek($storedWeek);

        $officient = Mockery::mock(Officient::class);

        $officient->shouldReceive('getPeople')->andThrow($this->rateLimitExceeded());

        $this->app->instance(Officient::class, $officient);

        $this->artisan('dashboard:fetch-officient-calendar')->assertSuccessful();

        $this->assertSame($storedWeek, OfficientStore::make()->week());
        $this->assertFalse(cache()->has('officient_active_people_2026-08-13'));

        Exceptions::assertNothingReported();

        Log::shouldHaveReceived('warning')->once();
    }

    public function testItDoesNotCacheAnIncompleteListOfPeopleWhenAPersonDetailRequestIsRateLimited(): void
    {
        $this->travelTo(Carbon::parse('2026-08-13 10:00', 'Europe/Brussels'));

        $officient = Mockery::mock(Officient::class);

        $officient->shouldReceive('getPeople')
            ->andReturn(collect([
                ['id' => 1, 'name' => 'Freek', 'email' => 'freek@example.com'],
            ]));

        $officient->shouldReceive('getPersonDetail')->andThrow($this->rateLimitExceeded());

        $this->app->instance(Officient::class, $officient);

        $this->artisan('dashboard:fetch-officient-calendar')->assertSuccessful();

        $this->assertFalse(cache()->has('officient_active_people_2026-08-13'));
    }

    public function testUnexpectedExceptionsAreNotSwallowed(): void
    {
        $this->travelTo(Carbon::parse('2026-08-13 10:00', 'Europe/Brussels'));

        $officient = $this->mockOfficientWithPeople();

        $officient->shouldReceive('getDayCalendar')
            ->andThrow(new RuntimeException('Something is broken'));

        $this->expectException(RuntimeException::class);

        $this->artisan('dashboard:fetch-officient-calendar');
    }

    public function testTheCalendarIsFetchedEveryFifteenMinutesOffTheTopOfTheHour(): void
    {
        $fetchEvent = collect(app(Schedule::class)->events())
            ->first(fn ($event) => str_contains($event->command, 'dashboard:fetch-officient-calendar'));

        $this->assertNotNull($fetchEvent);
        $this->assertSame('7,22,37,52 4-20 * * 1-5', $fetchEvent->expression);
        $this->assertTrue($fetchEvent->runInBackground);
    }

    private function mockOfficientWithPeople(): Officient
    {
        $officient = Mockery::mock(Officient::class);

        $officient->shouldReceive('getPeople')
            ->andReturn(collect([
                ['id' => 1, 'name' => 'Freek', 'email' => 'freek@example.com'],
            ]));

        $officient->shouldReceive('getPersonDetail')
            ->andReturn([
                'avatar' => 'https://example.com/1.jpg',
                'employment' => [
                    'first_employment_date' => '2020-01-01',
                    'last_employment_date' => null,
                ],
            ]);

        $this->app->instance(Officient::class, $officient);

        return $officient;
    }

    private function connectionFailure(): ConnectException
    {
        return new ConnectException('Connection timed out', new Request('GET', '/1.0/calendar/1/2026/8/13'));
    }

    private function rateLimitExceeded(): RateLimitExceeded
    {
        return RateLimitExceeded::forRequest(new ClientException(
            'Too many request. Max 30 per 5 seconds.',
            new Request('GET', '/1.0/calendar/1/2026/8/13'),
            new Response(429),
        ));
    }
}
