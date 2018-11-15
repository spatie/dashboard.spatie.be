<?php

namespace App\Events\Statistics;

use App\Events\DashboardEvent;

class NpmTotalsFetched extends DashboardEvent
{
    /** @var int */
    public $monthly;

    /** @var int */
    public $total;

    public function __construct(array $totals)
    {
        foreach ($totals as $sumName => $total) {
            $this->$sumName = $total;
        }
    }
}
