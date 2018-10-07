<?php

namespace App\Http\Controllers;

use App\Events\Temperature\TemperatureFetched;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

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
