<?php

namespace App\Events\Moody;

use App\Events\DashboardEvent;

class LastUpdatedRecords extends DashboardEvent
{
    public $records;
    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }
}
