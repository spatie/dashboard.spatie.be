<?php

namespace App\Events\RainForecast;

use App\Events\DashboardEvent;

class ForecastFetched extends DashboardEvent
{
    /** @var array */
    public $forecast;

    public function __construct(array $forecast)
    {
        $this->forecast = $forecast;
    }
}
