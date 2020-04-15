<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeComponentsServiceProvider extends ServiceProvider
{
    public function register()
    {

        Blade::component('components.dashboard', 'dashboard');
        Blade::component('components.tile', 'tile');
    }
}
