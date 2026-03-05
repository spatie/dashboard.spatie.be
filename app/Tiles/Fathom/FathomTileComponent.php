<?php

namespace App\Tiles\Fathom;

use Illuminate\Contracts\View\View;
use Spatie\Dashboard\Components\BaseTileComponent;

class FathomTileComponent extends BaseTileComponent
{
    public string $siteId = '';

    public string $label = '';

    public function render(): View
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
