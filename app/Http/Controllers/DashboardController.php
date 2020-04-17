<?php

namespace App\Http\Controllers;

use App\Services\TweetHistory\TweetHistory;

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
