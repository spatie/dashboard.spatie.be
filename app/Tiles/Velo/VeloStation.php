<?php

namespace App\Tiles\Velo;

class VeloStation
{
    private array $veloStationAttributes;

    public function __construct(array $veloStationAttributes)
    {
        $this->veloStationAttributes = $veloStationAttributes;
    }

    public function shortName(): string
    {
        return substr($this->veloStationAttributes['name'], 4);
    }

    public function numberOfBikesAvailable(): int
    {
        return $this->veloStationAttributes['bikes'];
    }

    public function displayClass(): string
    {
        if ($this->isEmpty()) {
            return 'line-through';
        }

        if ($this->isNearlyEmpty()) {
            return 'text-danger';
        }

        return '';
    }

    public function isEmpty(): bool
    {
        return $this->numberOfBikesAvailable() === 0;
    }

    public function isNearlyEmpty(): bool
    {
        return $this->numberOfBikesAvailable() < 3;
    }
}
