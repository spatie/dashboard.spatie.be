<?php

namespace App\Http\Livewire;

use App\Support\VeloStore;
use Livewire\Component;

class VeloComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('components.livewire.velo', [
            'stations' => VeloStore::make()->stations(),
        ]);
    }
}
