<?php

namespace App\Tiles\Climate;

use Illuminate\Contracts\View\View;
use Spatie\Dashboard\Components\BaseTileComponent;

class ClimateTileComponent extends BaseTileComponent
{
    public function render(): View
    {
        $climateStore = ClimateStore::make();

        return view('components.tiles.climate', [
            'indoorTemperature' => $climateStore->indoorTemperature(),
            'outdoorTemperature' => $climateStore->outdoorTemperature(),
            'acOn' => $climateStore->acOn(),
        ]);
    }
}
