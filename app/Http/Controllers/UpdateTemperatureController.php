<?php

namespace App\Http\Controllers;

use App\Support\WeatherStore;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class UpdateTemperatureController
{
    use ValidatesRequests;

    public function __invoke(Request $request)
    {
        $temperature = $this->validate($request, [
            'temperature' => 'required|numeric',
        ]);

        $temperature = round($temperature['temperature'], 1);

        WeatherStore::make()->setInsideTemperature($temperature);

        return 'ok';
    }
}
