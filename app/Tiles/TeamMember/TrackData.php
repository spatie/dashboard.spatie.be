<?php

namespace App\Tiles\TeamMember;

use Illuminate\Contracts\Support\Arrayable;

class TrackData implements Arrayable
{
    public function __construct(
        public string $artist,
        public string $album,
        public string $trackName,
        public string $url,
        public string $artwork,
        public ?int $durationInSeconds = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'artist' => $this->artist,
            'album' => $this->album,
            'trackName' => $this->trackName,
            'url' => $this->url,
            'artwork' => $this->artwork,
            'durationInSeconds' => $this->durationInSeconds,
        ];
    }
}
