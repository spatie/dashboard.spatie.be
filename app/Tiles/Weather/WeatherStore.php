<?php

namespace App\Tiles\Weather;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Dashboard\Models\Tile;
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\File;

class WeatherStore
{
    private Tile $tile;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName('weather');
    }

    public function setForecasts(array $forecasts): self
    {
        $this->tile->putData('forecasts', $forecasts);

        return $this;
    }

    public function forecasts(): array
    {
        return $this->tile->getData('forecasts') ?? [];
    }

    public function setWeatherReport(array $weatherReport): self
    {
        $this->tile->putData('weatherReport', $weatherReport);

        return $this;
    }

    public function outsideTemperature(): ?int
    {
        $weatherReport = $this->tile->getData('weatherReport');

        $temperature = Arr::get($weatherReport, 'main.temp');

        if (is_null($temperature)) {
            return null;
        }

        return (int)$temperature;
    }

    public function getEmoji(): string
    {
        $weatherReport = $this->tile->getData('weatherReport');

        $weatherId = Arr::get($weatherReport, 'weather.0.id');

        if (is_null($weatherId)) {
            return '🧐';
        }

        $group = ((string)$weatherId)[0];
        if ($group === 2) {
            return '⛈';
        }

        if ($group === 3) {
            return '☔';
        }

        if ($group === 5) {
            return '☔';
        }

        if ($group === 6) {
            return '☃';
        }

        if ($weatherId >= 700 && $weatherId <= 762) {
            return '🌫';
        }

        if ($weatherId === 781) {
            return '🌪';
        }

        if ($weatherId === 771) {
            return '💨';
        }

        if ($weatherId === 800) {
            $isNight = Str::endsWith(Arr::get($weatherReport, 'weather.0.icon'), 'n');

            return $isNight ? '🌌' : '☀';
        }

        if ($weatherId === 801) {
            return '⛅';
        }

        if ($group === 8) {
            return '☁';
        }

        return '🧐';
    }

    public function setInsideTemperature($temperature) : self
    {
        $this->tile->putData('insideTemperature', $temperature);

        return $this;
    }

    public function insideTemperateture(): ?int
    {
        return $this->tile->getData('insideTemperature');
    }

    public function getCity(): ?string
    {
        return $this->tile->getData('weatherReport.name');
    }
}
