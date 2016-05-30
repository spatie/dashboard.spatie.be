<?php

namespace App\Components\Packagist\Events;

use App\Components\DashboardEvent;

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
