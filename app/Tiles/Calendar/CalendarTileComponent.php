<?php

namespace App\Tiles\Calendar;

use App\Tiles\Calendar\CalendarStore;
use Livewire\Component;

class CalendarTileComponent extends Component
{
    /** @var string */
    public $calendarId;

    /** @var string */
    public $position;

    /** @var int */
    public $refreshInSeconds;

    public function mount(string $calendarId, string $position, int $refreshInSeconds = 60)
    {
        $this->calendarId = $calendarId;

        $this->position = $position;

        $this->refreshInSeconds = $refreshInSeconds;
    }

    public function render()
    {
        return view('components.tiles.calendar', [
            'events' => CalendarStore::make()->eventsForCalendarId($this->calendarId),
        ]);
    }
}
