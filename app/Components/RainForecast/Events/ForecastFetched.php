<?php

namespace App\Components\RainForecast\Events;

use App\Components\DashboardEvent;

class ForecastFetched extends DashboardEvent
{
    /** @var array */
    public $forecast;

    public function __construct(array $forecast)
    {
        $this->forecast = $forecast;
    }
}
