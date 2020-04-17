<?php

namespace App\Services\Trains;

use GuzzleHttp\Client;

class IRail
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getConnections(string $departureStationName, string $destinationStationName): array
    {
        $response = $this->client->get("/connections?from={$departureStationName}&to={$destinationStationName}&format=json&lang=nl");

        $connections = json_decode($response->getBody(), true)['connection'];

        return collect($connections)
            ->map(function (array $connection) {
                $departure = $connection['departure'];

                return [
                    'station' => $departure['direction']['name'],
                    'time' => $departure['time'],
                    'platform' => $departure['platform'],
                    'canceled' => (bool) $departure['canceled'],
                    'delay' => (int) $departure['delay'] / 60,
                ];
            })
            ->toArray();
    }
}
