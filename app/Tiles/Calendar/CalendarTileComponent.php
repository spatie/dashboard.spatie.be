<?php

namespace App\Tiles\Calendar;

use App\Tiles\Calendar\CalendarStore;
use Livewire\Component;

class CalendarTileComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('components.tiles.calendar', [
            'events' => CalendarStore::make()->events(),
        ]);
    }
}
