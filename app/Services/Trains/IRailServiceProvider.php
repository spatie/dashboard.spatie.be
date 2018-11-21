<?php

namespace App\Services\Trains;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class IRailServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IRail::class, function () {
            $client = new Client([
                'base_uri' => 'https://api.irail.be',
            ]);

            return new IRail($client);
        });
    }
}
