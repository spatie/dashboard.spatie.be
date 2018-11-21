<?php

namespace App\Services\Trains;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class TrainsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Trains::class, function () {
            $client = new Client([
                'base_uri' => 'https://api.irail.be',
            ]);

            return new Trains($client);
        });
    }

}