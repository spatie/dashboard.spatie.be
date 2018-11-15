<?php

namespace App\Services\Velo;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class VeloServiceProvider extends ServiceProvider
{
    public function register()
    {
        $appToken = config('services.slack.app_token');

        $this->app->singleton(Velo::class, function () use ($appToken) {
            $client = new Client([
                'base_uri' => 'https://www.velo-antwerpen.be',
            ]);

            return new Velo($client);
        });
    }
}
