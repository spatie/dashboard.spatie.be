<?php

namespace App\Console\Components\Music;

use Illuminate\Console\Command;
use Spatie\NowPlaying\NowPlaying;
use App\Events\Music\NothingPlaying;
use App\Events\Music\TrackIsPlaying;

class FetchCurrentTrack extends Command
{
    protected $signature = 'dashboard:fetch-current-track';

    protected $description = 'Fetch the currently playing track from lastFm';

    public function handle()
    {
        $lastFm = new NowPlaying(config('services.last-fm.api_key'));

        collect(config('services.last-fm.users'))->each(function(string $userName) use ($lastFm) {
            $currentTrack = $lastFm->getTrackInfo($userName);

            $currentTrack
                ? new TrackIsPlaying($currentTrack, $userName)
                : new NothingPlaying($userName);
        });
    }
}
