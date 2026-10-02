<?php

use App\Models\OhDearMessage;
use Illuminate\Support\Facades\Schedule;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\VeloTile\FetchVeloStationsCommand;
use Spatie\CalendarTile\FetchCalendarEventsCommand;
use App\Tiles\Climate\Commands\FetchClimateDataCommand;
use App\Tiles\CookieClub\Commands\FetchCookieClubOverviewCommand;
use Spatie\BelgianTrainsTile\FetchBelgianTrainsCommand;
use App\Tiles\NowPlaying\Commands\FetchTopArtistsCommand;
use App\Tiles\Statistics\Commands\FetchGitHubTotalsCommand;
use App\Tiles\Statistics\Commands\FetchPackagistTotalsCommand;
use App\Tiles\Officient\Commands\FetchOfficientCalendarCommand;
use Spatie\TimeWeatherTile\Commands\FetchOpenWeatherMapDataCommand;
use Spatie\TimeWeatherTile\Commands\FetchBuienradarForecastsCommand;

Schedule::command(FetchBelgianTrainsCommand::class)->everyTwoMinutes();
Schedule::command(FetchCalendarEventsCommand::class)->everyTenMinutes();
Schedule::command(FetchBuienradarForecastsCommand::class)->everyFiveMinutes();
Schedule::command(FetchOpenWeatherMapDataCommand::class)->everyFiveMinutes();
Schedule::command(FetchGitHubTotalsCommand::class)->everyThirtyMinutes();
Schedule::command(FetchPackagistTotalsCommand::class)->hourly();
Schedule::command(FetchVeloStationsCommand::class)->everyTwoMinutes();
Schedule::command(FetchOfficientCalendarCommand::class)->everyTenMinutes();
Schedule::command(FetchTopArtistsCommand::class)->everyTenMinutes();
Schedule::command(FetchClimateDataCommand::class)->everyMinute();
Schedule::command(FetchCookieClubOverviewCommand::class)
    ->everyFiveMinutes()
    ->weekdays()
    ->between('08:00', '18:00')
    ->timezone('Europe/Brussels');

Schedule::command('model:prune', [
    '--model' => [WebhookCall::class, OhDearMessage::class],
])->daily();
