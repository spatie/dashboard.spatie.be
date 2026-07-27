<?php

namespace App\Providers;

use App\Models\User;
use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use App\Livewire\DeployCheckerComponent;
use Illuminate\Support\Facades\Broadcast;
use App\Tiles\Officient\OfficientTileComponent;
use App\Livewire\ProductAnalyticsScreenComponent;
use App\Livewire\ScreenConditionCheckerComponent;
use App\Tiles\NowPlaying\NowPlayingTileComponent;
use App\Tiles\OhDear\OhDearMessagesTileComponent;
use App\Tiles\Statistics\StatisticsTileComponent;
use App\Tiles\TeamMember\TeamMemberTileComponent;
use Spatie\OhDearUptimeTile\OhDearUptimeTileComponent;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        OhDearUptimeTileComponent::showTile(fn (array $downSites) => count($downSites));

        Livewire::component('deploy-checker', DeployCheckerComponent::class);
        Livewire::component('team-member-tile', TeamMemberTileComponent::class);
        Livewire::component('statistics-tile', StatisticsTileComponent::class);
        Livewire::component('product-analytics-screen', ProductAnalyticsScreenComponent::class);
        Livewire::component('screen-condition-checker', ScreenConditionCheckerComponent::class);
        Livewire::component('now-playing-tile', NowPlayingTileComponent::class);
        Livewire::component('officient-tile', OfficientTileComponent::class);
        Livewire::component('oh-dear-messages-tile', OhDearMessagesTileComponent::class);

        Broadcast::routes();

        Broadcast::channel('dashboard', function (User $user) {
            return true;
        });
    }
}
