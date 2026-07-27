<?php

namespace App\Tiles\Climate;

use Spatie\Dashboard\Models\Tile;

class ClimateStore
{
    private Tile $tile;

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName('climate');
    }

    public static function make(): self
    {
        return new static();
    }

    public function setClimateData(float $indoorTemperature, float $outdoorTemperature, bool $acOn): self
    {
        $this->tile->putData('climate', [
            'indoorTemperature' => $indoorTemperature,
            'outdoorTemperature' => $outdoorTemperature,
            'acOn' => $acOn,
        ]);

        return $this;
    }

    public function indoorTemperature(): ?float
    {
        $temperature = $this->data()['indoorTemperature'] ?? null;

        return is_numeric($temperature) ? (float) $temperature : null;
    }

    public function outdoorTemperature(): ?float
    {
        $temperature = $this->data()['outdoorTemperature'] ?? null;

        return is_numeric($temperature) ? (float) $temperature : null;
    }

    public function acOn(): ?bool
    {
        $acOn = $this->data()['acOn'] ?? null;

        return is_bool($acOn) ? $acOn : null;
    }

    private function data(): array
    {
        $data = $this->tile->getData('climate');

        return is_array($data) ? $data : [];
    }
}
