<?php

namespace App\Tiles\TrainConnections;

use App\Tiles\Calendar\CalendarStore;
use Livewire\Component;

class TrainConnectionsComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('components.tiles.trainConnections', [
            'showTrains' => (now()->hour >= 16) && (now()->hour <= 20),
            'trainConnections' => TrainConnectionsStore::make()->trainConnections(),
        ]);
    }
}
