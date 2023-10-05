<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\BelgianTrainsTile\BelgianTrainsTileComponent;
use Spatie\OhDearUptimeTile\OhDearUptimeTileComponent;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        OhDearUptimeTileComponent::showTile(fn (array $downSites) => count($downSites));
    }
}
