<?php

namespace App\Tiles\Weather\Commands;

use Illuminate\Console\Command;
use App\Tiles\Weather\WeatherStore;
use App\Services\Buienradar\Buienradar;

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
