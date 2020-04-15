<?php

namespace App\Providers;

use App\Http\Livewire\TeamMemberComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireComponentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Livewire::component('team-member', TeamMemberComponent::class);
    }
}
