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

        foreach (config('services.last-fm.users') as $userName) {
            $currentTrack = $lastFm->getTrackInfo($userName);

            if ($currentTrack) {
                event(new TrackIsPlaying($currentTrack, $userName));

                return;
            }
        }

        event(new NothingPlaying());
    }
}
