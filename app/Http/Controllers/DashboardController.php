<?php

namespace App\Http\Controllers;

use App\Services\TweetHistory\TweetHistory;

class DashboardController
{
    public function __invoke()
    {
        return view('dashboard')->with([
            'pusherKey' => config('broadcasting.connections.pusher.key'),
            'clientConnectionPath' => config('websockets.client_connection_path'),
            'environment' => app()->environment(),
            'initialTweets' => TweetHistory::all(),
            'openWeatherMapKey' => config('services.open_weather_map.key'),
        ]);
    }
}
