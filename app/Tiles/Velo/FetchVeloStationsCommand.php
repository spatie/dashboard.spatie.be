<?php

namespace App\Tiles\Velo;

use App\Services\Velo\Velo;
use App\Tiles\Velo\VeloStore;
use Illuminate\Console\Command;
use App\Events\Velo\StationsFetched;

class FetchVeloStationsCommand extends Command
{
    protected $signature = 'dashboard:fetch-velo-stations';

    protected $description = 'Fetch Velo Stations';

    public function handle(Velo $velo)
    {
        $this->info('Fetching Velo stations...');

        $stations = $velo->getStations(config('services.velo.stations'));

        VeloStore::make()->setStations($stations);

        $this->info('All done!');
    }
}
