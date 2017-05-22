<?php

namespace App\Components\LastFm;

use App\Events\LastFm\NothingPlaying;
use App\Events\LastFm\TrackIsPlaying;
use Illuminate\Console\Command;
use Spatie\NowPlaying\NowPlaying;

class FetchCurrentTrack extends Command
{
    protected $signature = 'dashboard:lastfm';

    protected $description = 'Fetch the currently playing track from last.fm.';

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
