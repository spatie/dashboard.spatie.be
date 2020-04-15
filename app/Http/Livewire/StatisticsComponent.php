<?php

namespace App\Http\Livewire;

use App\Support\StatisticsStore;
use Livewire\Component;

class StatisticsComponent extends Component
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

        return view('components.livewire.statistics', [
            'gitHubStars' => $statisticsStore->gitHubStars(),
            'gitHubContributors' => $statisticsStore->gitHubContributors(),
            'gitHubIssues' => $statisticsStore->gitHubIssues(),
            'gitHubPullRequests' => $statisticsStore->gitHubPullRequests(),
            'packagistMonthly' => $statisticsStore->packagistMonthly(),
            'packagistTotal' => $statisticsStore->packagistTotal(),
        ]);
    }
}
