<?php

namespace App\Tiles\Fathom;

use Spatie\Dashboard\Models\Tile;

class FathomStore
{
    private Tile $tile;

    public static function find(string $siteId): self
    {
        return new static($siteId);
    }

    public function __construct(string $siteId)
    {
        $this->tile = Tile::firstOrCreateForName("fathom_{$siteId}");
    }

    public function setStats(array $array): void
    {
        foreach ($array as $key => $value) {
            $this->tile->putData($key, $value);
        }
    }

    public function current(): string
    {
        return $this->tile->getData('current') ?? '0';
    }

    public function visitors(): string
    {
        return $this->tile->getData('visitors') ?? '0';
    }

    public function views(): string
    {
        return $this->tile->getData('views') ?? '0';
    }

    public function bounceRate(): string
    {
        return $this->tile->getData('bounceRate') ?? '0';
    }

    public function eventCompletions(): array
    {
        return $this->tile->getData('eventCompletions') ?? [];
    }

    public function avgTimeOnSite(): string
    {
        return $this->tile->getData('avgTimeOnSite') ?? '0';
    }
}
