<?php

namespace App\Tiles\Statistics;

use Illuminate\Contracts\View\View;
use Spatie\Dashboard\Components\BaseTileComponent;

class StatisticsTileComponent extends BaseTileComponent
{
    public function render(): View
    {
        $statisticsStore = StatisticsStore::make();

        return view('components.tiles.statistics', [
            'gitHubStars' => $statisticsStore->gitHubStars(),
            'gitHubContributors' => $statisticsStore->gitHubContributors(),
            'gitHubIssues' => $statisticsStore->gitHubIssues(),
            'gitHubPullRequests' => $statisticsStore->gitHubPullRequests(),
            'packagistMonthly' => $statisticsStore->packagistMonthly(),
            'packagistTotal' => $statisticsStore->packagistTotal(),
        ]);
    }
}
