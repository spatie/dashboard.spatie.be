<?php

namespace App\Providers;

use App\Http\Components\TileComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeComponentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Blade::component('components.dashboard', 'dashboard');
        Blade::component(TileComponent::class, 'tile');
    }
}
