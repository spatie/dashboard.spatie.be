<?php

namespace App\Tiles\Officient;

use Illuminate\Contracts\View\View;
use Spatie\Dashboard\Components\BaseTileComponent;

class OfficientTileComponent extends BaseTileComponent
{
    public function render(): View
    {
        return view('components.tiles.officient', [
            'days' => OfficientStore::make()->week(),
        ]);
    }
}
