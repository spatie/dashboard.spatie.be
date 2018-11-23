<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TimeWeather\TemperatureFetched;
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

        event(new TemperatureFetched($temperature));

        return 'ok';
    }
}
