<?php

use Illuminate\Support\Facades\Schedule;
use Spatie\VeloTile\FetchVeloStationsCommand;
use Spatie\CalendarTile\FetchCalendarEventsCommand;
use App\Tiles\Climate\Commands\FetchClimateDataCommand;
use Spatie\BelgianTrainsTile\FetchBelgianTrainsCommand;
use App\Tiles\NowPlaying\Commands\FetchTopArtistsCommand;
use App\Tiles\TeamMember\Commands\FetchSlackStatusCommand;
use App\Tiles\Statistics\Commands\FetchGitHubTotalsCommand;
use App\Tiles\Statistics\Commands\FetchPackagistTotalsCommand;
use App\Tiles\Officient\Commands\FetchOfficientCalendarCommand;
use Spatie\TimeWeatherTile\Commands\FetchOpenWeatherMapDataCommand;
use Spatie\TimeWeatherTile\Commands\FetchBuienradarForecastsCommand;

Schedule::command(FetchBelgianTrainsCommand::class)->everyTwoMinutes();
Schedule::command(FetchCalendarEventsCommand::class)->everyTenMinutes();
Schedule::command(FetchBuienradarForecastsCommand::class)->everyFiveMinutes();
Schedule::command(FetchOpenWeatherMapDataCommand::class)->everyFiveMinutes();
Schedule::command(FetchSlackStatusCommand::class)->everyTenMinutes();
Schedule::command(FetchGitHubTotalsCommand::class)->everyThirtyMinutes();
Schedule::command(FetchPackagistTotalsCommand::class)->hourly();
Schedule::command(FetchVeloStationsCommand::class)->everyTwoMinutes();
Schedule::command(FetchOfficientCalendarCommand::class)->everyTenMinutes();
Schedule::command(FetchTopArtistsCommand::class)->everyTenMinutes();
Schedule::command(FetchClimateDataCommand::class)->everyMinute();
