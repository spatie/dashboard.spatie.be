<?php

namespace App\Tiles\Fathom;

use Livewire\Component;

class FathomTileComponent extends Component
{
    /** @var string */
    public $position;

    /** @var string */
    public $siteId;

    /** @var string */
    public $label;

    public function mount(string $position, string $label, string $siteId)
    {
        $this->position = $position;
        $this->siteId = $siteId;
        $this->label = $label;
    }

    public function render()
    {
        $fathomStore = FathomStore::find($this->siteId);

        return view('components.tiles.fathom', [
            'current' => $fathomStore->current(),
            'visitors' => $fathomStore->visitors(),
            'views' => $fathomStore->views(),
            'bounceRate' => $fathomStore->bounceRate(),
            'eventCompletions' => $fathomStore->eventCompletions(),
            'avgTimeOnSite' => $fathomStore->avgTimeOnSite(),
        ]);
    }
}
