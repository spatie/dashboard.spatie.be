<?php

namespace Tests\Feature;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    private array $everyFewMinutesFetches = [
        'dashboard:fetch-belgian-trains',
        'dashboard:fetch-calendar-events',
        'dashboard:fetch-buienradar-forecasts',
        'dashboard:fetch-open-weather-data',
        'dashboard:fetch-velo-stations',
        'dashboard:fetch-top-artists',
        'dashboard:fetch-climate-data',
    ];

    public function testEveryScheduledTaskOnlyRunsOnWeekdays(): void
    {
        collect(app(Schedule::class)->events())->each(function (Event $event) {
            $this->assertStringEndsWith('* * 1,2,3,4,5', $event->expression, $event->command);
            $this->assertSame('Europe/Brussels', $event->timezone, $event->command);
        });
    }

    public function testTheTilesRefreshAtTheStartOfAWeekday(): void
    {
        $this->assertEqualsCanonicalizing(
            $this->everyFewMinutesFetches,
            $this->commandsDueAt('2026-10-05 06:00'),
        );
    }

    public function testTheTilesKeepRefreshingUntilTheEndOfAWeekday(): void
    {
        $this->assertEqualsCanonicalizing(
            $this->everyFewMinutesFetches,
            $this->commandsDueAt('2026-10-05 22:00'),
        );
    }

    public function testTheCookieClubOverviewIsOnlyFetchedDuringOfficeHours(): void
    {
        $this->assertEqualsCanonicalizing(
            [...$this->everyFewMinutesFetches, 'dashboard:fetch-cookie-club-overview'],
            $this->commandsDueAt('2026-10-05 10:00'),
        );

        $this->assertNotContains('dashboard:fetch-cookie-club-overview', $this->commandsDueAt('2026-10-05 07:50'));
        $this->assertNotContains('dashboard:fetch-cookie-club-overview', $this->commandsDueAt('2026-10-05 18:05'));
    }

    public function testTheTasksWithTheirOwnMinuteRunDuringTheWindow(): void
    {
        $this->assertContains('model:prune', $this->commandsDueAt('2026-10-05 06:05'));
        $this->assertContains('dashboard:fetch-officient-calendar', $this->commandsDueAt('2026-10-05 06:07'));
        $this->assertContains('dashboard:fetch-packagist-totals', $this->commandsDueAt('2026-10-05 06:29'));
        $this->assertContains('dashboard:fetch-github-totals', $this->commandsDueAt('2026-10-05 21:39'));
    }

    #[DataProvider('sleepingTimes')]
    public function testNothingRunsWhileTheDashboardSleeps(string $brusselsTime): void
    {
        $this->assertSame([], $this->commandsDueAt($brusselsTime));
    }

    public static function sleepingTimes(): array
    {
        return [
            'weekday night' => ['2026-10-05 03:00'],
            'just before the window' => ['2026-10-05 05:58'],
            'just after the window' => ['2026-10-05 22:02'],
            'friday evening' => ['2026-10-02 23:00'],
            'saturday morning' => ['2026-10-03 06:05'],
            'saturday noon' => ['2026-10-03 12:00'],
            'sunday afternoon' => ['2026-10-04 15:00'],
        ];
    }

    /** @return array<int, string> */
    private function commandsDueAt(string $brusselsTime): array
    {
        $this->travelTo(Carbon::parse($brusselsTime, 'Europe/Brussels'));

        $this->refreshApplication();

        return collect(app(Schedule::class)->dueEvents($this->app))
            ->filter(fn (Event $event) => $event->filtersPass($this->app))
            ->map(fn (Event $event) => preg_match("/artisan'? ([\w:-]+)/", $event->command, $matches) ? $matches[1] : $event->command)
            ->values()
            ->all();
    }
}
