<?php

namespace App\Tiles\Velo;

use Livewire\Component;

class VeloTileComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('components.tiles.velo', [
            'stations' => VeloStore::make()->stations(),
        ]);
    }
}
