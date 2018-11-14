<?php

namespace App\Console\Components\Music;

use Illuminate\Console\Command;
use Spatie\NowPlaying\NowPlaying;
use App\Events\Music\TeamMemberPlayingNothing;
use App\Events\Music\TeamMemberPlayingTrack;

class FetchCurrentTracks extends Command
{
    protected $signature = 'dashboard:fetch-current-tracks';

    protected $description = 'Fetch the currently playing track from lastFm';

    protected $lastFmUsers = [
        'murze' => 'freek',
        'willemvb' => 'willem',
        'sebdedeyne' => 'seb',
        'ruben-va' => 'ruben',
        'AlexVanderbist' => 'alex',
        'wouterbrouwers' => 'wouter',
        'brendt_gd' => 'brent',
    ];

    public function handle()
    {
        $lastFm = new NowPlaying(config('services.last-fm.api_key'));

        collect($this->lastFmUsers)
            ->each(function (string $teamMemberName, string $lastFmUserName) use ($lastFm) {
                $currentTrack = $lastFm->getTrackInfo($lastFmUserName);

                $event = $currentTrack
                    ? new TeamMemberPlayingTrack($teamMemberName, $currentTrack)
                    : new TeamMemberPlayingNothing($teamMemberName);

                event($event);
            });
    }
}
