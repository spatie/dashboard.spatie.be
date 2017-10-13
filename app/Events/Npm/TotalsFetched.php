<?php

namespace App\Events\Npm;

use App\Events\DashboardEvent;

class TotalsFetched extends DashboardEvent
{
    /** @var int */
    public $daily;

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
