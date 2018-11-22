<?php

namespace App\Console\Components\Trains;

use App\Services\Trains\IRail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Events\Trains\TrainsFetched;

class FetchTrainsCommand extends Command
{
    protected $signature = 'dashboard:fetch-trains';

    protected $description = 'Fetch Trains Information';

    public function handle(IRail $iRail)
    {
        $this->info('Fetching trains from iRail...');

        $trains = collect(config('services.trains'))
            ->mapSpread(function (string $departure, string $destination) use ($iRail) {
                return $iRail->getConnections($departure, $destination);
            })
            ->flatten(1)
            ->sort(function(array $train) {
                return (int)$train['time'];
            })
            ->toArray();

        event(new TrainsFetched($trains));

        $this->info('All done!');
    }
}
