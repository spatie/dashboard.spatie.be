<?php

namespace App\Tiles\Statistics;

use App\Tiles\Statistics\StatisticsStore;
use Livewire\Component;

class StatisticsTileComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
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
