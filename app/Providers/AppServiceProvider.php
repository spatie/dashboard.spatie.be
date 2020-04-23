<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\OhDearUptimeTile\OhDearUptimeTileComponent;
use Spatie\BelgianTrainsTile\BelgianTrainsTileComponent;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        OhDearUptimeTileComponent::showTile(fn (array $downSites) => count($downSites));
        BelgianTrainsTileComponent::showTile(fn () => now()->hour >= 16 && now()->hour <= 20);
    }
}
