<?php

namespace App\Tiles\Weather\Commands;

use App\Tiles\Weather\WeatherStore;
use Illuminate\Console\Command;
use App\Services\Buienradar\Buienradar;
use App\Events\Buienradar\ForecastsFetched;

class FetchBuienradarForecastsCommand extends Command
{
    protected $signature = 'dashboard:fetch-buienradar-forecasts';

    protected $description = 'Fetch Buienradar forecasts';

    public function handle(Buienradar $buienradar)
    {
        $this->info('Fetching Buienradar forecasts...');

        $forecasts = $buienradar->getForecasts(
            config('services.buienradar.latitude'),
            config('services.buienradar.longitude')
        );

        WeatherStore::make()->setForecasts($forecasts);

        $this->info('All done!');
    }
}
