<?php

namespace App\Tiles\Calendar;

use App\Tiles\Calendar\CalendarStore;
use DateTime;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\GoogleCalendar\Event;

class FetchCalendarEventsCommand extends Command
{
    protected $signature = 'dashboard:fetch-calendar-events';

    protected $description = 'Fetch events from a Google Calendar';

    public function handle()
    {
        $this->info('Fetching calendar events...');

        $events = collect(Event::get(null, null, [], config('google-calendar.calendar_id')))
            ->map(function (Event $event) {
                $sortDate = $event->getSortDate();

                return [
                    'name' => $event->name,
                    'date' => Carbon::createFromFormat('Y-m-d H:i:s', $sortDate)->format(DateTime::ATOM),
                ];
            })
            ->unique('name')
            ->toArray();

        CalendarStore::make()->setEventsForCalendarId($events, config('google-calendar.calendar_id'));

        $this->info('All done!');
    }
}
