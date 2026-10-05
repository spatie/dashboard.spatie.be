<?php

namespace Tests\Feature;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    /** @var array<int, string> */
    private array $everyFewMinutesFetches = [
        'dashboard:fetch-belgian-trains',
        'dashboard:fetch-calendar-events',
        'dashboard:fetch-buienradar-forecasts',
        'dashboard:fetch-open-weather-data',
        'dashboard:fetch-velo-stations',
        'dashboard:fetch-top-artists',
        'dashboard:fetch-climate-data',
    ];

    public function testEveryCronExpressionIsLimitedToTheFetchWindow(): void
    {
        collect(app(Schedule::class)->events())->each(function (Event $event) {
            [, $hours, , , $daysOfWeek] = preg_split('/\s+/', $event->expression);

            $this->assertNotSame('*', $hours, $event->command);
            $this->assertSame('1-5', $daysOfWeek, $event->command);
            $this->assertSame('UTC', $event->timezone, $event->command);
        });
    }

    #[DataProvider('seasons')]
    public function testLaravelCloudGetsTheSameUtcExpressionsAllYearRound(string $utcTime): void
    {
        $this->assertSame([
            '*/10 4-20 * * 1-5',
            '*/10 4-20 * * 1-5',
            '*/10 4-20 * * 1-5',
            '*/2 4-20 * * 1-5',
            '*/2 4-20 * * 1-5',
            '*/5 4-20 * * 1-5',
            '*/5 4-20 * * 1-5',
            '*/5 6-16 * * 1-5',
            '29 4-20 * * 1-5',
            '39 4-20 * * 1-5',
            '5 4 * * 1-5',
            '7,22,37,52 4-20 * * 1-5',
        ], $this->cloudExpressionsAt($utcTime));
    }

    public static function seasons(): array
    {
        return [
            'summer' => ['2026-07-01 12:00'],
            'winter' => ['2026-12-01 12:00'],
        ];
    }

    #[DataProvider('brusselsWindowEdges')]
    public function testTheWindowCoversTheBrusselsWindowInEverySeason(string $brusselsTime): void
    {
        $this->assertEqualsCanonicalizing(
            $this->everyFewMinutesFetches,
            $this->commandsDueAt($brusselsTime, 'Europe/Brussels'),
        );
    }

    public static function brusselsWindowEdges(): array
    {
        return [
            'summer start' => ['2026-07-01 06:00'],
            'summer end' => ['2026-07-01 21:50'],
            'winter start' => ['2026-12-01 06:00'],
            'winter end' => ['2026-12-01 21:50'],
        ];
    }

    #[DataProvider('brusselsOfficeHoursEdges')]
    public function testTheOfficeHoursCoverTheBrusselsOfficeHoursInEverySeason(string $brusselsTime): void
    {
        $this->assertContains(
            'dashboard:fetch-cookie-club-overview',
            $this->commandsDueAt($brusselsTime, 'Europe/Brussels'),
        );
    }

    public static function brusselsOfficeHoursEdges(): array
    {
        return [
            'summer start' => ['2026-07-01 08:00'],
            'summer end' => ['2026-07-01 17:55'],
            'winter start' => ['2026-12-01 08:00'],
            'winter end' => ['2026-12-01 17:55'],
        ];
    }

    public function testTheTilesRefreshAtTheStartOfAWeekday(): void
    {
        $this->assertEqualsCanonicalizing(
            $this->everyFewMinutesFetches,
            $this->commandsDueAt('2026-10-05 04:00'),
        );
    }

    public function testTheTilesKeepRefreshingUntilTheEndOfAWeekday(): void
    {
        $this->assertEqualsCanonicalizing(
            $this->everyFewMinutesFetches,
            $this->commandsDueAt('2026-10-05 20:50'),
        );
    }

    public function testTheCookieClubOverviewIsOnlyFetchedDuringOfficeHours(): void
    {
        $this->assertEqualsCanonicalizing(
            [...$this->everyFewMinutesFetches, 'dashboard:fetch-cookie-club-overview'],
            $this->commandsDueAt('2026-10-05 10:00'),
        );

        $this->assertContains('dashboard:fetch-cookie-club-overview', $this->commandsDueAt('2026-10-05 06:00'));
        $this->assertContains('dashboard:fetch-cookie-club-overview', $this->commandsDueAt('2026-10-05 16:55'));
        $this->assertNotContains('dashboard:fetch-cookie-club-overview', $this->commandsDueAt('2026-10-05 05:50'));
        $this->assertNotContains('dashboard:fetch-cookie-club-overview', $this->commandsDueAt('2026-10-05 17:00'));
    }

    public function testTheTasksWithTheirOwnMinuteRunDuringTheWindow(): void
    {
        $this->assertContains('model:prune', $this->commandsDueAt('2026-10-05 04:05'));
        $this->assertContains('dashboard:fetch-officient-calendar', $this->commandsDueAt('2026-10-05 04:07'));
        $this->assertContains('dashboard:fetch-packagist-totals', $this->commandsDueAt('2026-10-05 04:29'));
        $this->assertContains('dashboard:fetch-github-totals', $this->commandsDueAt('2026-10-05 20:39'));
    }

    public function testTheClimateDataIsFetchedEveryTenMinutes(): void
    {
        $this->assertContains('dashboard:fetch-climate-data', $this->commandsDueAt('2026-10-05 10:10'));
        $this->assertNotContains('dashboard:fetch-climate-data', $this->commandsDueAt('2026-10-05 10:01'));
        $this->assertNotContains('dashboard:fetch-climate-data', $this->commandsDueAt('2026-10-05 10:05'));
    }

    #[DataProvider('sleepingTimes')]
    public function testNothingRunsWhileTheDashboardSleeps(string $utcTime): void
    {
        $this->assertSame([], $this->commandsDueAt($utcTime));
    }

    public static function sleepingTimes(): array
    {
        return [
            'weekday night' => ['2026-10-05 02:00'],
            'just before the window' => ['2026-10-05 03:58'],
            'end of the window' => ['2026-10-05 21:00'],
            'friday evening' => ['2026-10-02 23:00'],
            'monday just after midnight' => ['2026-10-05 00:05'],
            'saturday morning' => ['2026-10-03 04:05'],
            'saturday noon' => ['2026-10-03 12:00'],
            'sunday afternoon' => ['2026-10-04 15:00'],
        ];
    }

    /** @return array<int, string> */
    private function commandsDueAt(string $time, string $timezone = 'UTC'): array
    {
        $this->travelTo(Carbon::parse($time, $timezone));

        $this->refreshApplication();

        return collect(app(Schedule::class)->dueEvents($this->app))
            ->filter(fn (Event $event) => $event->filtersPass($this->app))
            ->map(fn (Event $event) => $this->commandName($event))
            ->values()
            ->all();
    }

    private function commandName(Event $event): string
    {
        if (! preg_match("/artisan'? ([\w:-]+)/", $event->command, $matches)) {
            return $event->command;
        }

        return $matches[1];
    }

    /**
     * The UTC expressions Laravel Cloud reads from schedule:list to know when to wake the app.
     *
     * @return array<int, string>
     */
    private function cloudExpressionsAt(string $utcTime): array
    {
        $this->travelTo(Carbon::parse($utcTime, 'UTC'));

        Artisan::call('schedule:list', ['--timezone' => 'UTC']);

        return collect(explode(PHP_EOL, trim(Artisan::output())))
            ->map(fn (string $line) => implode(' ', array_slice(preg_split('/\s+/', trim($line)), 0, 5)))
            ->sort()
            ->values()
            ->all();
    }
}
