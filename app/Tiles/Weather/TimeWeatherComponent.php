<?php

namespace App\Tiles\Weather;

use App\Tiles\Weather\WeatherStore;
use Livewire\Component;

class TimeWeatherComponent extends Component
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
