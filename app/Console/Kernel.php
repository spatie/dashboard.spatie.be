<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Components\Calendar\FetchCalendarEvents::class,
        \App\Console\Components\Statistics\FetchGitHubTotals::class,
        \App\Console\Components\InternetConnection\SendHeartbeat::class,
        \App\Console\Components\Statistics\FetchNpmTotals::class,
        \App\Console\Components\Music\FetchCurrentTracks::class,
        \App\Console\Components\Statistics\FetchPackagistTotals::class,
        \App\Console\Components\Tasks\FetchTasks::class,
        \App\Console\Components\Twitter\ListenForMentions::class,
        \App\Console\Components\Twitter\SendFakeTweet::class,
        \App\Console\Components\Velo\FetchStations::class,
        UpdateDashboard::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dashboard:fetch-calendar-events')->everyMinute();
        $schedule->command('dashboard:fetch-current-tracks')->everyMinute();
        $schedule->command('dashboard:send-heartbeat')->everyMinute();
        $schedule->command('dashboard:fetch-tasks')->everyFiveMinutes();
        $schedule->command('dashboard:fetch-github-totals')->everyThirtyMinutes();
        $schedule->command('dashboard:fetch-packagist-totals')->hourly();
        $schedule->command('dashboard:fetch-npm-totals')->hourly();
        $schedule->command('dashboard:fetch-velo-stations')->everyFiveMinutes();
    }
}
