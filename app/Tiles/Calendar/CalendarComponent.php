<?php

namespace App\Tiles\Calendar;

use App\Tiles\Calendar\CalendarStore;
use Livewire\Component;

class CalendarComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('components.livewire.calendar', [
            'events' => CalendarStore::make()->events(),
        ]);
    }
}
