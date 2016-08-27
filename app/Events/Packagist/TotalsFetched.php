<?php

namespace App\Events\Packagist;

use App\Events\DashboardEvent;

class TotalsFetched extends DashboardEvent
{
    public $daily;

    public $monthly;

    public $total;

    public $stars;

    public function __construct($totals)
    {
        foreach ($totals as $sumName => $total) {
            $this->$sumName = $total;
        }
    }
}
