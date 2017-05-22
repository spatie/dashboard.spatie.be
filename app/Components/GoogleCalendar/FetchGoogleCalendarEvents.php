<?php

namespace App\Components\GoogleCalendar;

use App\Events\GoogleCalendar\EventsFetched;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Spatie\GoogleCalendar\Event;

class FetchGoogleCalendarEvents extends Command
{
    protected $signature = 'dashboard:calendar';

    protected $description = 'Fetch Google Calendar events.';

    public function handle()
    {
        $events = collect(Event::get())
            ->filter(function (Event $event) {
                return $event->name != 'Poetsvrouwman';
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
