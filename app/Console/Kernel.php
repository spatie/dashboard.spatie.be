<?php

namespace App\Console;

use App\Console\Components\Buienradar\FetchBuienradarForecastsCommand;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Components\Trains\FetchTrainsCommand;
use App\Console\Components\Velo\FetchVeloStationsCommand;
use App\Console\Components\TeamMember\FetchTasksCommand;
use App\Console\Components\TeamMember\FetchStatusCommand;
use App\Console\Components\Dashboard\SendHeartbeatCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Components\Calendar\FetchCalendarEventsCommand;
use App\Console\Components\Statistics\FetchGitHubTotalsCommand;
use App\Console\Components\Dashboard\DetermineAppearanceCommand;
use App\Console\Components\TeamMember\FetchCurrentTracksCommand;
use App\Console\Components\Statistics\FetchPackagistTotalsCommand;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(FetchTrainsCommand::class)->everyMinute();
        $schedule->command(FetchCalendarEventsCommand::class)->everyMinute();
        $schedule->command(FetchCurrentTracksCommand::class)->everyMinute();
        $schedule->command(SendHeartbeatCommand::class)->everyMinute();
        $schedule->command(FetchVeloStationsCommand::class)->everyMinute();
        $schedule->command(DetermineAppearanceCommand::class)->everyMinute();
        $schedule->command(FetchBuienradarForecastsCommand::class)->everyFiveMinutes();
        $schedule->command(FetchTasksCommand::class)->everyFiveMinutes();
        $schedule->command(FetchStatusCommand::class)->everyFiveMinutes();
        $schedule->command(FetchGitHubTotalsCommand::class)->everyThirtyMinutes();
        $schedule->command(FetchPackagistTotalsCommand::class)->hourly();
        $schedule->command('websockets:clean')->daily();
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
