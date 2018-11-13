<?php

namespace App\Console\Components\Velo;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Events\Velo\StationsFetched;

class FetchStations extends Command
{
    protected $signature = 'dashboard:fetch-velo-stations';

    protected $description = 'Fetch Vélo Stations';

    public function handle()
    {
        $allowedStations = config('services.velo.stations');

        $client = new Client();
        $response = $client->get('https://www.velo-antwerpen.be/availability_map/getJsonObject');

        $data = collect(json_decode($response->getBody()));

        $stations = $data->filter(function ($station) use ($allowedStations) {
            return in_array($station->id, $allowedStations);
        })->mapWithKeys(function ($station) use ($allowedStations) {
            // To sort the stations in the same order as requested
            $key = array_search($station->id, $allowedStations);

            return [$key => $station];
        })->toArray();

        event(new StationsFetched($stations));
    }
}
