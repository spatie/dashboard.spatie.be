<?php

namespace App\Events\Calendar;

use App\Events\DashboardEvent;

class EventsFetched extends DashboardEvent
{
    /** @var array */
    public $events;
    public $birthday;
    public $ontime;

    public function __construct(array $events)
    {
        $this->events = $events;
        $this->shit ='s';
    }
}
