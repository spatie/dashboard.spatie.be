<?php

namespace App\Services;

use GuzzleHttp\Client;

class Velo
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getStations(array $stationIds = []): array
    {
        $response = $this->client->get('https://www.velo-antwerpen.be/availability_map/getJsonObject');

        return collect(json_decode($response->getBody()))
            ->filter(function ($station) use ($stationIds) {
                return in_array($station->id, $stationIds);
            })->mapWithKeys(function ($station) use ($stationIds) {
                $key = array_search($station->id, $stationIds);

                return [$key => $station];
            })->toArray();
    }
}