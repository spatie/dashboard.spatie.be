<?php

namespace App\Console\Components\Trains;

use App\Services\Trains\IRail;
use Illuminate\Console\Command;
use App\Events\Trains\TrainConnectionsFetched;

class FetchTrainsCommand extends Command
{
    protected $signature = 'dashboard:fetch-train-connections';

    protected $description = 'Fetch Trains Information';

    public function handle(IRail $iRail)
    {
        $this->info('Fetching trainConnections from iRail...');

        $trainConnections = collect(config('services.train_connections'))
            ->map(function (array $connection) use ($iRail) {
                $trains = $iRail->getConnections($connection['departure'], $connection['destination']);

                return ['label' => $connection['label'], 'trains' => $trains];
            })
            ->toArray();

        event(new TrainConnectionsFetched($trainConnections));

        $this->info('All done!');
    }
}
