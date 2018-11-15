<?php

namespace App\Events\Statistics;

use App\Events\DashboardEvent;

class GitHubTotalsFetched extends DashboardEvent
{
    /** @var int */
    public $stars;

    /** @var int */
    public $issues;

    /** @var int */
    public $pullRequests;

    /** @var int */
    public $contributors;

    public function __construct(array $totals)
    {
        foreach ($totals as $statisticName => $total) {
            $this->$statisticName = $total;
        }
    }
}
