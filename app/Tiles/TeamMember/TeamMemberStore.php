<?php

namespace App\Tiles\TeamMember;

use Spatie\Dashboard\Models\Tile;

class TeamMemberStore
{
    private Tile $tile;

    public static function find(string $name): self
    {
        return new static($name);
    }

    public function __construct(string $name)
    {
        $this->tile = Tile::firstOrCreateForName("member_{$name}");
    }

    public function setStatusEmoji(string $emoji): self
    {
        $this->tile->putData('statusEmoji', $emoji);

        return $this;
    }

    public function statusEmoji(): string
    {
        return $this->tile->getData('statusEmoji') ?? '';
    }

    public function setNowPlaying(array $track): self
    {
        $this->tile->putData('nowPlaying', $track);

        return $this;
    }

    public function setNothingPlaying(): self
    {
        $this->tile->putData('nowPlaying', []);

        return $this;
    }

    public function nowPlaying(): array
    {
        return $this->tile->getData('nowPlaying') ?? [];
    }
}
