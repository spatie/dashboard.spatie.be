<?php

namespace App\Services\Buienradar;

use GuzzleHttp\Client;

class Buienradar
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getForecasts(string $latitude, string $longitude): array
    {
        $response = $this->client->get("forecast/json/?lat={$latitude}&lon={$longitude}");

        return collect(json_decode($response->getBody())->forecasts)
            ->map(function ($forecast) {
                return [
                    'time' => $forecast->datetime,
                    'rain' => $forecast->precipitation,
                ];
            })
            ->take(12)
            ->toArray();
    }
}
