<?php

namespace App\Events\Buienradar;

use App\Events\DashboardEvent;

class ForecastsFetched extends DashboardEvent
{
    /** @var array */
    public $forecasts;

    public function __construct(array $forecasts)
    {
        $this->forecasts = $forecasts;
    }
}
