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

$fetchDays = config('dashboard.fetch_window.days');
$fetchHours = config('dashboard.fetch_window.hours');

Schedule::cron("* {$fetchHours} * * {$fetchDays}")
    ->timezone(config('dashboard.fetch_window.timezone'))
    ->group(function () use ($fetchDays, $fetchHours) {
        Schedule::command(FetchBelgianTrainsCommand::class)->everyTwoMinutes();
        Schedule::command(FetchCalendarEventsCommand::class)->everyTenMinutes();
        Schedule::command(FetchBuienradarForecastsCommand::class)->everyFiveMinutes();
        Schedule::command(FetchOpenWeatherMapDataCommand::class)->everyFiveMinutes();
        Schedule::command(FetchGitHubTotalsCommand::class)
            ->cron("39 {$fetchHours} * * {$fetchDays}")
            ->runInBackground();
        Schedule::command(FetchPackagistTotalsCommand::class)->cron("29 {$fetchHours} * * {$fetchDays}");
        Schedule::command(FetchVeloStationsCommand::class)->everyTwoMinutes();
        Schedule::command(FetchOfficientCalendarCommand::class)
            ->cron("7,22,37,52 {$fetchHours} * * {$fetchDays}")
            ->runInBackground();
        Schedule::command(FetchTopArtistsCommand::class)->everyTenMinutes();
        Schedule::command(FetchClimateDataCommand::class)->everyTenMinutes();
        Schedule::command(FetchCookieClubOverviewCommand::class)->cron("*/5 8-17 * * {$fetchDays}");

        Schedule::command('model:prune', [
            '--model' => [WebhookCall::class, OhDearMessage::class],
        ])->dailyAt('06:05');
    });
