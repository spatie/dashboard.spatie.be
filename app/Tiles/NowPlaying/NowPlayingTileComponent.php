<?php

namespace App\Tiles\NowPlaying;

use App\Models\NowPlayingSong;
use Illuminate\Contracts\View\View;
use Spatie\Dashboard\Components\BaseTileComponent;

class NowPlayingTileComponent extends BaseTileComponent
{
    public function render(): View
    {
        return view('components.tiles.nowPlaying', [
            'song' => NowPlayingSong::where('updated_at', '>=', now()->subMinutes(10))->latest()->first(),
        ]);
    }
}
