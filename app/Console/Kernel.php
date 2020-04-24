<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Spatie\TimeWeatherTile\Commands\FetchBuienradarForecastsCommand;
use Spatie\TimeWeatherTile\Commands\FetchOpenWeatherMapDataCommand;
use Spatie\VeloTile\FetchVeloStationsCommand;
use Spatie\CalendarTile\FetchCalendarEventsCommand;
use App\Tiles\TeamMember\Commands\FetchTasksCommand;
use Spatie\BelgianTrainsTile\FetchBelgianTrainsCommand;
use App\Tiles\TeamMember\Commands\FetchSlackStatusCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Tiles\Statistics\Commands\FetchGitHubTotalsCommand;
use App\Tiles\TeamMember\Commands\FetchCurrentTracksCommand;
use App\Tiles\Statistics\Commands\FetchPackagistTotalsCommand;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(FetchBelgianTrainsCommand::class)->everyMinute();
        $schedule->command(FetchCalendarEventsCommand::class)->everyMinute();
        $schedule->command(FetchCurrentTracksCommand::class)->everyMinute();
        $schedule->command(FetchBuienradarForecastsCommand::class)->everyFiveMinutes();
        $schedule->command(FetchOpenWeatherMapDataCommand::class)->everyFiveMinutes();
        $schedule->command(FetchTasksCommand::class)->everyFiveMinutes();
        $schedule->command(FetchSlackStatusCommand::class)->everyMinute();
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
