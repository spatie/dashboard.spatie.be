<?php

namespace App\Http\Livewire;

use App\Support\WeatherStore;
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

        return view('components.livewire.timeWeather', [
            'city' => $weatherStore->getCity(),
            'forecasts' => $weatherStore->forecasts(),
            'outsideTemperature'  => $weatherStore->outsideTemperature(),
            'insideTemperature' => $weatherStore->insideTemperateture(),
            'emoji' => $weatherStore->getEmoji(),
        ]);
    }
}
