<?php

namespace App\Providers;

use App\Tiles\Statistics\StatisticsTileComponent;
use App\Tiles\TeamMember\TeamMemberTileComponent;
use App\Tiles\Weather\TimeWeatherTileComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireComponentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Livewire::component('team-member', TeamMemberTileComponent::class);
        Livewire::component('statistics', StatisticsTileComponent::class);
        Livewire::component('clock-tile', TimeWeatherTileComponent::class);
    }
}
