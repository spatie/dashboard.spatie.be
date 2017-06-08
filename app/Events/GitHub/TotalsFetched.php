<?php

namespace App\Events\GitHub;

use App\Events\DashboardEvent;

class TotalsFetched extends DashboardEvent
{
    /** @var int */
    public $stars;

    /** @var int */
    public $issues;

    /** @var int */
    public $pullRequests;

    /** @var int */
    public $contributors;

    /** @var int */
    public $numberOfRepos;

    public function __construct(array $totals)
    {
        foreach ($totals as $sumName => $total) {
            $this->$sumName = $total;
        }
    }
}
