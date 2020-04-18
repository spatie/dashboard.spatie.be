<?php

namespace App\Providers;

use App\Tiles\Calendar\CalendarTileComponent;
use App\Tiles\Statistics\StatisticsTileComponent;
use App\Tiles\TeamMember\TeamMemberTileComponent;
use App\Tiles\TrainConnections\TrainConnectionsTileComponent;
use App\Tiles\Uptime\UptimeTileComponent;
use App\Tiles\Weather\TimeWeatherTileComponent;
use App\Tiles\Twitter\TwitterTileComponent;
use App\Tiles\Velo\VeloTileComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireComponentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Livewire::component('team-member', TeamMemberTileComponent::class);
        Livewire::component('calendar', CalendarTileComponent::class);
        Livewire::component('statistics', StatisticsTileComponent::class);
        Livewire::component('twitter', TwitterTileComponent::class);
        Livewire::component('time-weather', TimeWeatherTileComponent::class);
        Livewire::component('velo', VeloTileComponent::class);
        Livewire::component('uptime', UptimeTileComponent::class);
        Livewire::component('train-connections', TrainConnectionsTileComponent::class);
    }
}
