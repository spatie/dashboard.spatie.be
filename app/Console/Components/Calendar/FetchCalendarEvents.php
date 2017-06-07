<?php

namespace App\Console\Components\Calendar;

use DateTime;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\GoogleCalendar\Event;
use App\Events\Calendar\EventsFetched;

class FetchCalendarEvents extends Command
{
    protected $signature = 'dashboard:fetch-calendar-events';

    protected $description = 'Fetch events from Google Calendar';

    public function handle()
    {
        $events = collect(Event::get())
            ->reject(function (Event $event) {
                return $event->name === 'Poetsvrouwman';
            })->map(function (Event $event) {
                return [
                    'name' => $event->name,
                    'date' => Carbon::createFromFormat('Y-m-d H:i:s', $event->getSortDate())->format(DateTime::ATOM),
                ];
            })
            ->unique('name')
            ->toArray();

        event(new EventsFetched($events));
    }
}
