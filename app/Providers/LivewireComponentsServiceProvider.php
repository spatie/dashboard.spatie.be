<?php

namespace App\Providers;

use App\Tiles\Fathom\FathomTileComponent;
use App\Tiles\Statistics\StatisticsTileComponent;
use App\Tiles\TeamMember\TeamMemberTileComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireComponentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Livewire::component('team-member-tile', TeamMemberTileComponent::class);
        Livewire::component('statistics-tile', StatisticsTileComponent::class);
        Livewire::component('fathom-tile', FathomTileComponent::class);
    }
}
