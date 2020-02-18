<?php

namespace App\Console\Components\Velo;

use App\Events\Velo\StationsFetched;
use App\Services\Velo\Velo;
use Illuminate\Console\Command;

class FetchVeloStationsCommand extends Command
{
    protected $signature = 'dashboard:fetch-velo-stations';

    protected $description = 'Fetch Velo Stations';

    public function handle(Velo $velo)
    {
        $this->info('Fetching Velo stations...');

        $stations = $velo->getStations(config('services.velo.stations'));

        event(new StationsFetched($stations));

        $this->info('All done!');
    }
}
