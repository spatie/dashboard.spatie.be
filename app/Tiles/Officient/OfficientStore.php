<?php

namespace App\Tiles\Officient;

use Spatie\Dashboard\Models\Tile;

class OfficientStore
{
    private Tile $tile;

    public static function make(): self
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName('officient');
    }

    public function setWeek(array $days): self
    {
        $this->tile->putData('week', $days);

        return $this;
    }

    public function week(): array
    {
        return $this->tile->getData('week') ?? [];
    }
}
