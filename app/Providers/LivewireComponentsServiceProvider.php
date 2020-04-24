<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use App\Tiles\Weather\TimeWeatherTileComponent;
use App\Tiles\Statistics\StatisticsTileComponent;
use App\Tiles\TeamMember\TeamMemberTileComponent;

class LivewireComponentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Livewire::component('team-member-tile', TeamMemberTileComponent::class);
        Livewire::component('statistics-tile', StatisticsTileComponent::class);
    }
}
