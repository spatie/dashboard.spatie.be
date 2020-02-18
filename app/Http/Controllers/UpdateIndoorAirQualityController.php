<?php

namespace App\Http\Controllers;

use App\Events\TimeWeather\IndoorAirQualityFetched;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class UpdateIndoorAirQualityController
{
    use ValidatesRequests;

    public function __invoke(Request $request)
    {
        $iaq = $this->validate($request, [
            'indoorAirQuality' => 'required|numeric',
        ]);

        $iaq = round($iaq['iaq'], 1);

        event(new IndoorAirQualityFetched($iaq));

        return 'ok';
    }
}
