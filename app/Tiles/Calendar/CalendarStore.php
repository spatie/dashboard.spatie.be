<?php

namespace App\Tiles\Calendar;

use App\Models\Tile;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarStore
{
    private Tile $tile;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName('calendar');
    }

    public function setEventsForCalendarId(array $events, string $calendarId): self
    {
        $this->tile->putData('events_' . $calendarId, $events);

        return $this;
    }

    public function eventsForCalendarId(string $calendarId): Collection
    {
        return collect($this->tile->getData('events_' . $calendarId) ?? [])
            ->map(function (array $event) {
                $event['date'] = Carbon::createFromTimeString($event['date']);
                $event['withinWeek'] = $event['date']->diffInDays() < 7;
                return $event;
            });
    }
}
