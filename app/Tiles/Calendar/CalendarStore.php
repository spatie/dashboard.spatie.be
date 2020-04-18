<?php

namespace App\Tiles\Calendar;

use Carbon\Carbon;
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class CalendarStore
{
    private Valuestore $valuestore;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $path = storage_path("app/dashboard");

        File::makeDirectory($path, 0755, true, true);

        $this->valuestore = Valuestore::make("{$path}/calendar.json");
    }

    public function setEventsForCalendarId(array $events, string $calendarId): self
    {
        $this->valuestore->put('events_' . $calendarId, $events);

        return $this;
    }

    public function eventsForCalendarId(string $calendarId): Collection
    {
        return collect($this->valuestore->get('events_' . $calendarId) ?? [])
            ->map(function (array $event) {
                $event['date'] = Carbon::createFromTimeString($event['date']);
                $event['withinWeek'] = $event['date']->diffInDays() < 7;
                return $event;
            });
    }
}
