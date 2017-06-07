<?php

namespace App\Console;

use Exception;
use App\Exceptions\Handler;
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
        \App\Components\GitHub\FetchTasks::class,
        \App\Components\GitHub\FetchStatistics::class,
        \App\Components\GoogleCalendar\FetchCalendarEvents::class,
        \App\Components\LastFm\FetchCurrentTrack::class,
        \App\Components\Packagist\FetchTotals::class,
        \App\Components\InternetConnectionStatus\SendHeartbeat::class,
        \App\Components\RainForecast\FetchRainForecast::class,
        \App\Components\Twitter\ListenForMentions::class,
        SendFakeTweet::class,
        UpdateDashboard::class,
    ];

    /**
     * The Artisan commands that are scheduled to run on a certain frequency.
     *
     * @var array
     */
    protected $scheduled = [
        \App\Components\LastFm\FetchCurrentTrack::class => 'everyMinute',
        \App\Components\GoogleCalendar\FetchCalendarEvents::class => 'everyMinute',
        \App\Components\GitHub\FetchTasks::class => 'everyFiveMinutes',
        \App\Components\GitHub\FetchStatistics::class => 'everyThirtyMinutes',
        \App\Components\InternetConnectionStatus\SendHeartbeat::class => 'everyMinute',
        \App\Components\Packagist\FetchTotals::class => 'hourly',
        \App\Components\RainForecast\FetchRainForecast::class => 'everyMinute',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        foreach ($this->scheduled as $command => $frequency) {
            try {
                $schedule->command($command)->$frequency();
            } catch (Exception $e) {
                $this->app->make(Handler::class)->report($e);
            }
        }
    }
}
