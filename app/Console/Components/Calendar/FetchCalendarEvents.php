<?php

namespace App\Console\Components\Calendar;

use DateTime;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\GoogleCalendar\Event;
use App\CalendarList;
use App\Events\Calendar\EventsFetched;

use Google_Service_Calendar_CalendarList;
use Spatie\GoogleCalendar\GoogleCalendarFactory;

class FetchCalendarEvents extends Command
{
    protected $signature = 'dashboard:fetch-calendar-events';

    protected $description = 'Fetch events from a Google Calendar';

    public function handle()
    {

        // $calendarId = $calendarId ?? config('google-calendar.calendar_id');

        // $calendarService = GoogleCalendarFactory::createForCalendarId($calendarId)->getService();
        // foreach ($calendarService->calendarList->listCalendarList() as $calendarListItem) {
        //     dump($calendarListItem->getId());
        //     $allEvents = $calendarService->events->listEvents($calendarListItem->getId(),['maxResults'=>5, 'orderBy'=>'updated','timeMin' => '2018-06-03T10:00:00-07:00','singleEvents'=>'true']);
        //     foreach ($allEvents as $allEvent) {
        //         dump($allEvent->summary);
        //     }
        //     // dump($calendarListItem->getId());
        // }






        $events = collect(Event::get())
            ->map(function (Event $event) {
                $sortDate = $event->getSortDate();

                return [
                    'name' => $event->name,
                    'date' => Carbon::createFromFormat('Y-m-d H:i:s', $sortDate)->format(DateTime::ATOM),
                ];
            })
            ->unique('name')
            ->toArray();

        event(new EventsFetched($events));
    }
}
