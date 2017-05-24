<?php

namespace App\Components\RainForecast;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Events\RainForecast\ForecastFetched;

class FetchRainForecast extends Command
{
    protected $signature = 'dashboard:rain';

    protected $description = 'Fetch the rain forecast.';

    public function handle()
    {
        $lat = env('RAINFORECAST_LATITUDE');
        $lng = env('RAINFORECAST_LONGITUDE');

        $responseBody = (string) (new Client())
                ->get("http://gps.buienradar.nl/getrr.php?lat={$lat}&lon={$lng}")
                ->getBody();

        $forecast = $this->getForecastFromResponseBody($responseBody);

        event(new ForecastFetched($forecast));
    }

    public function getForecastFromResponseBody(string $responseBody): array
    {
        $forecastItems = explode("\r\n", $responseBody);

        return collect($forecastItems)
            ->reject(function ($forecastItem) {
                return $forecastItem == '';
            })
            ->map(function (string $forecastItem) {
                list($chanceOfRain, $time) = explode('|', $forecastItem);

                $chanceOfRain = intval(intval($chanceOfRain) / 255 * 100);

                $carbon = $this->getCarbonFromTime($time);

                $minutes = $carbon->diffInMinutes();

                if ($carbon->isPast()) {
                    $minutes *= -1;
                }

                return compact('chanceOfRain', 'minutes');
            })
            ->filter(function (array $foreCastItem) {
                return $foreCastItem['minutes'] > 0;
            })
            ->take(6)
            ->values()
            ->toArray();
    }

    public function getCarbonFromTime(string $time) : Carbon
    {
        $dateTime = Carbon::createFromFormat('H:i', $time);

        if (starts_with($time, '00') && Carbon::now()->hour == 23) {
            $dateTime->addDay();
        }

        return $dateTime;
    }
}
