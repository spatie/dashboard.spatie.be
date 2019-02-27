<?php

namespace App\Services\Forecast;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class ForecastServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ForecastApi::class, function () {
            return new ForecastApi(
                new Client([
                    'base_uri' => 'https://api.forecastapp.com/',
                    'headers' => [
                        'Forecast-Account-ID' => config('services.forecast.account_id'),
                        'Authorization' => 'Bearer ' . config('services.forecast.token'),
                    ],
                ])
            );
        });
    }
}
