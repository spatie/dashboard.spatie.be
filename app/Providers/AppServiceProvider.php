<?php

namespace App\Providers;

use App\Models\User;
use App\Livewire\DeployCheckerComponent;
use App\Tiles\Fathom\FathomTileComponent;
use App\Tiles\NowPlaying\NowPlayingTileComponent;
use App\Tiles\Statistics\StatisticsTileComponent;
use App\Tiles\TeamMember\TeamMemberTileComponent;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Spatie\OhDearUptimeTile\OhDearUptimeTileComponent;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        OhDearUptimeTileComponent::showTile(fn (array $downSites) => count($downSites));

        Livewire::component('deploy-checker', DeployCheckerComponent::class);
        Livewire::component('team-member-tile', TeamMemberTileComponent::class);
        Livewire::component('statistics-tile', StatisticsTileComponent::class);
        Livewire::component('fathom-tile', FathomTileComponent::class);
        Livewire::component('now-playing-tile', NowPlayingTileComponent::class);

        Broadcast::routes();

        Broadcast::channel('dashboard', function (User $user) {
            return true;
        });
    }
}
