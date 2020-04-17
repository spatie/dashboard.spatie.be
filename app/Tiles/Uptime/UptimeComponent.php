<?php

namespace App\Tiles\Uptime;

use Livewire\Component;

class UptimeComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('components.livewire.uptime', [
            'downSites' => UptimeStore::make()->downSites(),
        ]);
    }
}
