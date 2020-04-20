<?php

namespace App\Console;

use App\Tiles\Weather\Commands\FetchOpenWeatherDataCommand;
use Illuminate\Console\Scheduling\Schedule;
use App\Tiles\TeamMember\Commands\FetchTasksCommand;
use App\Tiles\TeamMember\Commands\FetchSlackStatusCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Tiles\Statistics\Commands\FetchGitHubTotalsCommand;
use App\Tiles\TeamMember\Commands\FetchCurrentTracksCommand;
use App\Tiles\Statistics\Commands\FetchPackagistTotalsCommand;
use App\Tiles\Weather\Commands\FetchBuienradarForecastsCommand;
use Spatie\BelgianTrainsTile\FetchBelgianTrainsCommand;
use Spatie\CalendarTile\FetchCalendarEventsCommand;
use Spatie\VeloTile\FetchVeloStationsCommand;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(FetchBelgianTrainsCommand::class)->everyMinute();
        $schedule->command(FetchCalendarEventsCommand::class)->everyMinute();
        $schedule->command(FetchCurrentTracksCommand::class)->everyMinute();
        $schedule->command(SendHeartbeatCommand::class)->everyMinute();
        $schedule->command(DetermineAppearanceCommand::class)->everyMinute();
        $schedule->command(FetchBuienradarForecastsCommand::class)->everyFiveMinutes();
        $schedule->command(FetchOpenWeatherDataCommand::class)->everyFiveMinutes();
        $schedule->command(FetchTasksCommand::class)->everyFiveMinutes();
        $schedule->command(FetchSlackStatusCommand::class)->everyFiveMinutes();
        $schedule->command(FetchGitHubTotalsCommand::class)->everyThirtyMinutes();
        $schedule->command(FetchPackagistTotalsCommand::class)->hourly();
        $schedule->command(FetchVeloStationsCommand::class)->everyMinute();
    }

    public function commands()
    {
        $commandDirectories = glob(app_path('Tiles/*'), GLOB_ONLYDIR);
        $commandDirectories[] = app_path('Console');

        collect($commandDirectories)->each(function (string $commandDirectory) {
            $this->load($commandDirectory);
        });
    }
}
