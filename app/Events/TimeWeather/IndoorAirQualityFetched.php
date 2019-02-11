<?php

namespace App\Events\TimeWeather;

use App\Events\DashboardEvent;

class IndoorAirQualityFetched extends DashboardEvent
{
    /** @var float */
    public $indoorAirQuality;

    public function __construct(float $iaq)
    {
        $this->indoorAirQuality = $iaq;
    }
}
