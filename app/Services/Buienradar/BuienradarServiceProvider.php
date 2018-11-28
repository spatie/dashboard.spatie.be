<?php

namespace App\Services\Buienradar;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class BuienradarServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Buienradar::class, function () {
            $client = new Client([
                'base_uri' => 'https://graphdata.buienradar.nl/',
            ]);

            return new Buienradar($client);
        });
    }
}
