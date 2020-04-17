<?php

namespace App\Providers;

use App\Http\Livewire\CalendarComponent;
use App\Http\Livewire\StatisticsComponent;
use App\Http\Livewire\TeamMemberComponent;
use App\Http\Livewire\TweetsComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireComponentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Livewire::component('team-member', TeamMemberComponent::class);
        Livewire::component('calendar', CalendarComponent::class);
        Livewire::component('statistics', StatisticsComponent::class);
        Livewire::component('tweets', TweetsComponent::class);
    }
}
