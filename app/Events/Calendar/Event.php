<?php
namespace  App\Events\Calendar;

use Carbon\Carbon;
use Google_Service_Calendar_Event;
use Illuminate\Support\Collection;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;
use Spatie\GoogleCalendar\GoogleCalendarFactory;

class Event {

    public static function get(Carbon $startDateTime = null, Carbon $endDateTime = null, array $queryParameters = [], string $calendarId = null) : Collection
    {
        $googleCalendar = GoogleCalendarFactory::createForCalendarId($calendarId);
        $googleEvents = $googleCalendar->listEvents($startDateTime, $endDateTime, $queryParameters);
        $useUserOrder = isset($queryParameters['orderBy']);

        return collect($googleEvents)
        ->map(function (Google_Service_Calendar_Event $event) use ($calendarId) {
            return GoogleCalendarEvent::createFromGoogleCalendarEvent($event, $calendarId);
        })
        ->sortBy(function (GoogleCalendarEvent $event, $index) use ($useUserOrder) {
            if ($useUserOrder) {
                return $index;
            }

            return $event->sortDate;
        })
        ->values();
    }
}