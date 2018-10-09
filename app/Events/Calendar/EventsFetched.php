<?php

namespace App\Events\Calendar;

use App\Events\DashboardEvent;

class EventsFetched extends DashboardEvent
{
    /** @var array */
    public $calendarEvents;

    public function __construct(array $calendarEvents)
    {
        $this->calendarEvents = $calendarEvents;
    }
}
