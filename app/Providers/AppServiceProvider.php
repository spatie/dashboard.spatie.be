<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\BelgianTrainsTile\BelgianTrainsTileComponent;
use Spatie\OhDearUptimeTile\OhDearUptimeTileComponent;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        OhDearUptimeTileComponent::showTile(fn (array $downSites) => count($downSites) > 0);
        BelgianTrainsTileComponent::showTile(fn () => now()->hour >= 16 && now()->hour <= 21);
    }
}
