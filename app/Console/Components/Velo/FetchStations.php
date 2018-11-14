<?php

namespace App\Console\Components\Velo;

use App\Services\Velo;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Events\Velo\StationsFetched;

class FetchStations extends Command
{
    protected $signature = 'dashboard:fetch-velo-stations';

    protected $description = 'Fetch Velo Stations';

    public function handle()
    {
        $velo = new Velo(new Client());

        $stations = $velo->getStations(config('services.velo.stations'));

        event(new StationsFetched($stations));
    }
}
