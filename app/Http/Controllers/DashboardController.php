<?php

namespace App\Http\Controllers;

use App\Support\TweetHistory\TweetHistory;

class DashboardController
{
    public function __invoke()
    {
        return view('dashboard')->with([
            'environment' => app()->environment(),
            'openWeatherMapKey' => config('services.open_weather_map.key'),
        ]);
    }
}
