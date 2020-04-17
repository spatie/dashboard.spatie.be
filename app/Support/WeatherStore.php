<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Valuestore\Valuestore;

class WeatherStore
{
    private Valuestore $valuestore;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $path = storage_path("app/dashboard");

        File::makeDirectory($path, 0755, true, true);

        $this->valuestore = Valuestore::make("{$path}/weather.json");
    }

    public function setForecasts(array $forecasts): self
    {
        $this->valuestore->put('forecasts', $forecasts);

        return $this;
    }

    public function forecasts(): array
    {
        return $this->valuestore->get('forecasts') ?? [];
    }

    public function setWeatherReport(array $weatherReport): self
    {
        $this->valuestore->put('weatherReport', $weatherReport);

        return $this;
    }

    public function outsideTemperature(): ?int
    {
        $weatherReport = $this->valuestore->get('weatherReport');

        $temperature = Arr::get($weatherReport, 'main.temp');

        if (is_null($temperature)) {
            return null;
        }

        return (int)$temperature;
    }

    public function getEmoji(): string
    {
        $weatherReport = $this->valuestore->get('weatherReport');

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
        $this->valuestore->put('insideTemperature', $temperature);

        return $this;
    }

    public function insideTemperateture(): ?int
    {
        return $this->valuestore->get('insideTemperature');
    }

    public function getCity(): ?string
    {
        return $this->valuestore->get('weatherReport.name');
    }


}
