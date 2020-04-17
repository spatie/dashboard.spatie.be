<?php

namespace App\Providers;

use App\Tiles\Calendar\CalendarComponent;
use App\Tiles\Statistics\StatisticsComponent;
use App\Tiles\TeamMember\TeamMemberComponent;
use App\Tiles\Weather\TimeWeatherComponent;
use App\Tiles\Twitter\TwitterComponent;
use App\Tiles\Velo\VeloComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireComponentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Livewire::component('team-member', TeamMemberComponent::class);
        Livewire::component('calendar', CalendarComponent::class);
        Livewire::component('statistics', StatisticsComponent::class);
        Livewire::component('twitter', TwitterComponent::class);
        Livewire::component('time-weather', TimeWeatherComponent::class);
        Livewire::component('velo', VeloComponent::class);
    }
}
