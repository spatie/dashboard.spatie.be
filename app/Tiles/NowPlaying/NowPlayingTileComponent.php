<?php

namespace App\Tiles\NowPlaying;

use App\Models\NowPlayingSong;
use Illuminate\Contracts\View\View;
use Spatie\Dashboard\Components\BaseTileComponent;

class NowPlayingTileComponent extends BaseTileComponent
{
    public function render(): View
    {
        $song = NowPlayingSong::current();

        return view('components.tiles.nowPlaying', [
            'song' => $song,
            'topArtist' => $song ? null : (NowPlayingStore::make()->topArtists()[0] ?? null),
        ]);
    }
}
