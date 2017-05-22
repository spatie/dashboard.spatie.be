<?php

namespace App\Events\GitHub;

use App\Events\DashboardEvent;

class StatisticsFetched extends DashboardEvent
{
    public $issues;

    public $pullRequests;

    public $forks;

    public $contributors;

    public function __construct(array $totals)
    {
        foreach ($totals as $sumName => $total) {
            $this->$sumName = $total;
        }
    }
}