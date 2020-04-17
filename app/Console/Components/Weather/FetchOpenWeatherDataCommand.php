<?php

namespace App\Console\Components\Weather;

use App\Support\WeatherStore;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchOpenWeatherDataCommand extends Command
{
    protected $signature = 'dashboard:fetch-openweather-date';

    protected $description = 'Fetch Openweather data';

    public function handle()
    {
        $city = 'Antwerp';

        $key = config('services.open_weather_map.key');

        $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$key}&units=metric";

        $weatherReport = Http::get($url)->json();

        WeatherStore::make()->setWeatherReport($weatherReport);

        $this->info('All done!');
    }
}
