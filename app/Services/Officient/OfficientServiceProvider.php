<?php

namespace App\Services\Officient;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class OfficientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $token = config('services.officient.token');

        $this->app->singleton(Officient::class, function () use ($token) {
            $client = new Client([
                'base_uri' => 'https://api.officient.io',
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                ],
            ]);

            return new Officient($client);
        });
    }
}
