<?php

namespace App\Services\Velo;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class VeloServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Velo::class, function () {
            $client = new Client([
                'base_uri' => 'https://www.velo-antwerpen.be',
            ]);

            return new Velo($client);
        });
    }
}
