<?php

namespace App\Components\RainForecast;

use App\Events\RainForecast\ForecastFetched;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchRainForecast extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:rain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the rain forecast.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lat = 51.2194;
        $lng = 4.4025;

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
