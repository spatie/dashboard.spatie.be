<?php

namespace App\Console\Components\Buienradar;

use App\Events\Buienradar\ForecastsFetched;
use App\Services\Buienradar\Buienradar;
use Illuminate\Console\Command;

class FetchBuienradarForecastsCommand extends Command
{
    protected $signature = 'dashboard:fetch-buienradar-forecasts';

    protected $description = 'Fetch Buienradar forecasts';

    public function handle(Buienradar $buienradar)
    {
        $this->info('Fetching Buienradar forecasts...');

        $forecasts = $buienradar->getForecasts(config('services.buienradar.latitude'), config('services.buienradar.longitude'));

        event(new ForecastsFetched($forecasts));

        $this->info('All done!');
    }
}
