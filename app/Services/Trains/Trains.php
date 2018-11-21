<?php

namespace App\Services\Trains;

use GuzzleHttp\Client;

class Trains
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getLiveboard(): array
    {
        $response = $this->client->get('/liveboard?station='.config('services.trains.station').'&arrdep=departure&alerts=true&format=json&lang=nl');

        $liveboard = json_decode($response->getBody());

        return [
            'name' => $liveboard->stationinfo->name,
            'departures' => collect($liveboard->departures->departure)
                ->filter(function ($departure) {
                    return ! in_array($departure->station, config('services.trains.excluded'));
                })->map(function ($departure) {
                    return [
                        'station' => $departure->station,
                        'time' => $departure->time,
                        'platform' => $departure->platform,
                        'canceled' => (bool) $departure->canceled,
                        'delay' => (int) $departure->delay / 60,
                    ];
                })->toArray(),
        ];
    }
}