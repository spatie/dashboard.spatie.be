<?php

namespace App\Events\Temperature;

use App\Events\DashboardEvent;

class TemperatureFetched extends DashboardEvent
{
    /** @var float */
    public $temperature;

    public function __construct(float $temperature)
    {
        $this->temperature = $temperature;
    }
}
