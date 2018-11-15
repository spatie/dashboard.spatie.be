<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TimeWeather\TemperatureFetched;

class UpdateTemperatureController
{
    public function __invoke(Request $request)
    {
        $temperature = $request->validate($request, [
            'temperature' => 'required|numeric',
        ]);

        $temperature = round($temperature['temperature'], 1);

        event(new TemperatureFetched($temperature));

        return 'ok';
    }
}
