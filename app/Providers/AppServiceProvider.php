<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\OhDearUptimeTile\OhDearUptimeTileComponent;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        OhDearUptimeTileComponent::showTile(fn (array $downSites) => count($downSites) > 0);
    }
}
