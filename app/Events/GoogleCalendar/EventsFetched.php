<?php

namespace App\Events\GoogleCalendar;

use App\Events\DashboardEvent;

class EventsFetched extends DashboardEvent
{
    /** @var array */
    public $events;

    public function __construct(array $events)
    {
        $this->events = $events;
    }
}
