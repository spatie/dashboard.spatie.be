<?php

namespace App\Tiles\OhDear;

use Spatie\Dashboard\Models\Tile;
use Spatie\OhDearUptimeTile\OhDearUptimeStore;

/**
 * The package store memoizes its tile in a static variable, which goes stale
 * in a long running queue worker. This store reads the tile fresh every time.
 */
class FreshOhDearUptimeStore extends OhDearUptimeStore
{
    protected function getTile(): Tile
    {
        return Tile::firstOrCreateForName('ohDearUptime');
    }
}
