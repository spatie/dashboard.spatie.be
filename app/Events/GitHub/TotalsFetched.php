<?php

namespace App\Events\GitHub;

use App\Events\DashboardEvent;

class TotalsFetched extends DashboardEvent
{
    public $stars;

    public $issues;

    public $pullRequests;

    public $contributors;

    public $numberOfRepos;

    public function __construct(array $totals)
    {
        foreach ($totals as $sumName => $total) {
            $this->$sumName = $total;
        }
    }
}
