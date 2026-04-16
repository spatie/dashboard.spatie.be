<?php

use App\Tiles\Fathom\Commands\FetchFathomStatistics;
use App\Tiles\NowPlaying\Commands\FetchTopArtistsCommand;
use App\Tiles\Officient\Commands\FetchOfficientCalendarCommand;
use App\Tiles\Statistics\Commands\FetchGitHubTotalsCommand;
use App\Tiles\Statistics\Commands\FetchPackagistTotalsCommand;
use App\Tiles\TeamMember\Commands\FetchSlackStatusCommand;
use Illuminate\Support\Facades\Schedule;
use Spatie\BelgianTrainsTile\FetchBelgianTrainsCommand;
use Spatie\CalendarTile\FetchCalendarEventsCommand;
use Spatie\TimeWeatherTile\Commands\FetchBuienradarForecastsCommand;
use Spatie\TimeWeatherTile\Commands\FetchOpenWeatherMapDataCommand;
use Spatie\VeloTile\FetchVeloStationsCommand;

Schedule::command(FetchBelgianTrainsCommand::class)->everyMinute();
Schedule::command(FetchCalendarEventsCommand::class)->everyMinute();
Schedule::command(FetchBuienradarForecastsCommand::class)->everyFiveMinutes();
Schedule::command(FetchOpenWeatherMapDataCommand::class)->everyFiveMinutes();
Schedule::command(FetchSlackStatusCommand::class)->everyTenMinutes();
Schedule::command(FetchGitHubTotalsCommand::class)->everyThirtyMinutes();
Schedule::command(FetchPackagistTotalsCommand::class)->hourly();
Schedule::command(FetchVeloStationsCommand::class)->everyMinute();
Schedule::command(FetchFathomStatistics::class)->hourly();
Schedule::command(FetchOfficientCalendarCommand::class)->everyTenMinutes();
Schedule::command(FetchTopArtistsCommand::class)->everyFiveMinutes();
