<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dashboard:fetch-calendar-events')->everyMinute();
        $schedule->command('dashboard:fetch-current-tracks')->everyMinute();
        $schedule->command('dashboard:send-heartbeat')->everyMinute();
        $schedule->command('dashboard:fetch-velo-stations')->everyMinute();
        $schedule->command('dashboard:determine-appearance')->everyMinute();
        $schedule->command('dashboard:fetch-tasks')->everyFiveMinutes();
        $schedule->command('dashboard:fetch-team-member-status')->everyFiveMinutes();
        $schedule->command('dashboard:fetch-github-totals')->everyThirtyMinutes();
        $schedule->command('dashboard:fetch-packagist-totals')->hourly();
        $schedule->command('dashboard:fetch-npm-totals')->hourly();
    }

    public function commands()
    {
        $commandDirectries = glob(app_path('Console/Components/*'), GLOB_ONLYDIR);
        $commandDirectries[] = app_path('Console');

        collect($commandDirectries)->each(function (string $commandDirectory) {
            $this->load($commandDirectory);
        });
    }
}
