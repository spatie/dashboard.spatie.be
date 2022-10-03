<?php

namespace App\Providers;

use App\Tiles\Coffee\CoffeeTileComponent;
use App\Tiles\Fathom\FathomTileComponent;
use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use App\Tiles\Statistics\StatisticsTileComponent;
use App\Tiles\TeamMember\TeamMemberTileComponent;

class LivewireComponentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Livewire::component('team-member-tile', TeamMemberTileComponent::class);
        Livewire::component('statistics-tile', StatisticsTileComponent::class);
        Livewire::component('fathom-tile', FathomTileComponent::class);
    }
}
