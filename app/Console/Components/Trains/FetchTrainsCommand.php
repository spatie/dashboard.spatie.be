<?php

namespace App\Console\Components\Trains;

use App\Services\Trains\IRail;
use Illuminate\Console\Command;
use App\Events\Trains\TrainsFetched;

class FetchTrainsCommand extends Command
{
    protected $signature = 'dashboard:fetch-trains';

    protected $description = 'Fetch Trains Information';

    public function handle(IRail $iRail)
    {
        $this->info('Fetching Trains information...');

        $trains = collect(config('services.trains'))
            ->mapSpread(function (string $departure, string $destination) use ($iRail) {
                return $iRail->getConnections($departure, $destination);
            })
            ->flatten(1)
            ->sort('time')
            ->toArray();

        event(new TrainsFetched($trains));

        $this->info('All done!');
    }
}
