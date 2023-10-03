<?php

namespace App\Console;

use App\Tiles\Fathom\Commands\FetchFathomStatistics;
use App\Tiles\Statistics\Commands\FetchGitHubTotalsCommand;
use App\Tiles\Statistics\Commands\FetchPackagistTotalsCommand;
use App\Tiles\TeamMember\Commands\FetchCurrentTracksCommand;
use App\Tiles\TeamMember\Commands\FetchSlackStatusCommand;
use App\Tiles\TeamMember\Commands\FetchTasksCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\AttendancesTile\FetchAttendancesCommand;
use Spatie\BelgianTrainsTile\FetchBelgianTrainsCommand;
use Spatie\CalendarTile\FetchCalendarEventsCommand;
use Spatie\TimeWeatherTile\Commands\FetchBuienradarForecastsCommand;
use Spatie\TimeWeatherTile\Commands\FetchOpenWeatherMapDataCommand;
use Spatie\VeloTile\FetchVeloStationsCommand;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(FetchBelgianTrainsCommand::class)->everyMinute();
        $schedule->command(FetchCalendarEventsCommand::class)->everyMinute();
        $schedule->command(FetchAttendancesCommand::class)->everyFiveMinutes();
        $schedule->command(FetchCurrentTracksCommand::class)->everyMinute();
        $schedule->command(FetchBuienradarForecastsCommand::class)->everyFiveMinutes();
        $schedule->command(FetchOpenWeatherMapDataCommand::class)->everyFiveMinutes();
        //$schedule->command(FetchTasksCommand::class)->everyFiveMinutes();
        $schedule->command(FetchSlackStatusCommand::class)->everyMinute();
        $schedule->command(FetchGitHubTotalsCommand::class)->everyThirtyMinutes();
        $schedule->command(FetchPackagistTotalsCommand::class)->hourly();
        $schedule->command(FetchVeloStationsCommand::class)->everyMinute();
        $schedule->command(FetchFathomStatistics::class)->everyMinute();
    }

    public function commands(): void
    {
        $commandDirectories = glob(app_path('Tiles/*'), GLOB_ONLYDIR);
        $commandDirectories[] = app_path('Console');

        collect($commandDirectories)->each(function (string $commandDirectory) {
            $this->load($commandDirectory);
        });
    }
}
