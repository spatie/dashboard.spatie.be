<?php

namespace App\Tiles\Weather;

use Livewire\Component;

class TimeWeatherTileComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        $weatherStore = WeatherStore::make();

        return view('components.tiles.timeWeather', [
            'city' => $weatherStore->getCity(),
            'forecasts' => $weatherStore->forecasts(),
            'outsideTemperature'  => $weatherStore->outsideTemperature(),
            'insideTemperature' => $weatherStore->insideTemperateture(),
            'emoji' => $weatherStore->getEmoji(),
        ]);
    }
}
