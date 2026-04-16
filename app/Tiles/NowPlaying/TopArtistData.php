<?php

namespace App\Tiles\NowPlaying;

use Illuminate\Contracts\Support\Arrayable;

class TopArtistData implements Arrayable
{
    public function __construct(
        public string $artist,
        public int $plays,
        public ?string $artworkUrl = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'artist' => $this->artist,
            'plays' => $this->plays,
            'artworkUrl' => $this->artworkUrl,
        ];
    }
}
