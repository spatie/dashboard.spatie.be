<?php

namespace App\Tiles\NowPlaying;

use Spatie\Dashboard\Models\Tile;

class NowPlayingStore
{
    private Tile $tile;

    public static function make(): self
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName('nowPlaying');
    }

    /**
     * @param  array<int, TopArtistData>  $artists
     */
    public function setTopArtists(array $artists): self
    {
        $this->tile->putData('topArtists', array_map(fn (TopArtistData $artist) => $artist->toArray(), $artists));

        return $this;
    }

    /**
     * @return array<int, TopArtistData>
     */
    public function topArtists(): array
    {
        $data = $this->tile->getData('topArtists') ?? [];

        return array_map(fn (array $artist) => new TopArtistData(...$artist), $data);
    }
}
