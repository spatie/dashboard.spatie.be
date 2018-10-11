<?php

namespace App\Console\Components\Calendar;

use DateTime;
use Carbon\Carbon;
use App\CalendarList;
use App\Events\Calendar\Event as Event;
use App\Events\Calendar\EventsFetched;
use Google_Service_Calendar_CalendarList;
use Illuminate\Console\Command;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;
use Spatie\GoogleCalendar\GoogleCalendarFactory;


class FetchCalendarEvents extends Command
{
    protected $signature = 'dashboard:fetch-calendar-events';

    protected $description = 'Fetch events from a Google Calendar';

    public function handle()
    {
        $calendarId = $calendarId ?? config('google-calendar.calendar_id');
        $calendarService = GoogleCalendarFactory::createForCalendarId($calendarId)->getService();
        // $startDate = Carbon::parse('today');
        // $endDate = Carbon::parse('tomorrow');
        $startDate = Carbon::parse('next friday');
        $endDate = Carbon::parse('next saturday');

        $queryParameters = [
            'maxResults' => 4,
            'singleEvents' => 'true',
        ];

        foreach ($calendarService->calendarList->listCalendarList(['minAccessRole' => 'owner']) as $calendarListItem) {
            $calendarId = $calendarListItem->getId();
            dump($calendarListItem->getId());
            $allEvents = collect(Event::get($startDate, $endDate, $queryParameters, $calendarId))
                ->map(function (GoogleCalendarEvent $event) {
                    $sortDate = $event->getSortDate();

            dump(json_encode($event->attendees));
                    return [
                        'name' => $event->name,
                        // 'date' => Carbon::createFromFormat('Y-m-d H:i:s', $sortDate)->format(DateTime::ATOM),
                        'description' => $event->description,
                        // 'attendees' => $event->attendees,
                    ];
                })
                ->unique('name')
                ->toArray();

            $events[$calendarListItem->getSummary()] = [
                'calendarName' => $calendarListItem->getSummary(),
                'events' => $allEvents,
            ];
        }
            // dump($events);

        event(new EventsFetched($events));
    }
}
