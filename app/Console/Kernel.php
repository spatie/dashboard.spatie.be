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
        \App\Console\Components\Calendar\FetchCalendarEvents::class,
        \App\Console\Components\GitHub\FetchTotals::class,
        \App\Console\Components\InternetConnection\SendHeartbeat::class,
        \App\Console\Components\Music\FetchCurrentTrack::class,
        \App\Console\Components\Packagist\FetchTotals::class,
        \App\Console\Components\Tasks\FetchTasks::class,
        \App\Console\Components\Twitter\ListenForMentions::class,
        \App\Console\Components\Twitter\SendFakeTweet::class,
        UpdateDashboard::class,
    ];

    /**
     * The Artisan commands that are scheduled to run on a certain frequency.
     *
     * @var array
     */
    protected $scheduled = [
        \App\Console\Components\Calendar\FetchCalendarEvents::class => 'everyMinute',
        \App\Console\Components\Music\FetchCurrentTrack::class => 'everyMinute',
        \App\Console\Components\InternetConnection\SendHeartbeat::class => 'everyMinute',
        \App\Console\Components\Tasks\FetchTasks::class => 'everyFiveMinutes',
        \App\Console\Components\GitHub\FetchTotals::class => 'everyThirtyMinutes',
        \App\Console\Components\Packagist\FetchTotals::class => 'hourly',
    ];
}
