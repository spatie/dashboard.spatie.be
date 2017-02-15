<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Components\GitHub\FetchGitHubFileContent::class,
        \App\Components\GoogleCalendar\FetchGoogleCalendarEvents::class,
        \App\Components\LastFm\FetchCurrentTrack::class,
        \App\Components\Packagist\FetchTotals::class,
        \App\Components\InternetConnectionStatus\SendHeartbeat::class,
        \App\Components\RainForecast\FetchRainForecast::class,
        \App\Components\Twitter\ListenForMentions::class,
        SendFakeTweet::class,
        UpdateDashboard::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(\App\Components\LastFm\FetchCurrentTrack::class)->everyMinute();
        $schedule->command(\App\Components\GoogleCalendar\FetchGoogleCalendarEvents::class)->everyFiveMinutes();
        $schedule->command(\App\Components\GitHub\FetchGitHubFileContent::class)->everyFiveMinutes();
        $schedule->command(\App\Components\InternetConnectionStatus\SendHeartbeat::class)->everyMinute();
        $schedule->command(\App\Components\Packagist\FetchTotals::class)->hourly();
        $schedule->command(\App\Components\RainForecast\FetchRainForecast::class)->everyMinute();
    }
}
