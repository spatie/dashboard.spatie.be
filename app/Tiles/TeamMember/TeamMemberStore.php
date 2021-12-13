<?php

namespace App\Tiles\TeamMember;

use Carbon\Carbon;
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

    public function setNowPlaying(TrackData $trackData): self
    {
        $this->tile->putData('nowPlaying', $trackData->toArray());

        return $this;
    }

    public function setNothingPlaying(): self
    {
        $this->tile->putData('nowPlaying', null);

        return $this;
    }

    public function nowPlaying(): ?TrackData
    {
        return $this->tile->getData('nowPlaying')
            ? new TrackData(...$this->tile->getData('nowPlaying'))
            : null;
    }

    public function setLastUpdate(Carbon $dateTime): self
    {
        $this->tile->putData('lastUpdate', $dateTime);

        return $this;
    }

    public function lastUpdate(): ?Carbon
    {
        return $this->tile->getData('lastUpdate') ?
            Carbon::parse($this->tile->getData('lastUpdate'))
            : null;
    }
}
