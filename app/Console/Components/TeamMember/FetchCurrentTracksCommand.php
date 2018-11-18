<?php

namespace App\Console\Components\TeamMember;

use Illuminate\Console\Command;
use Spatie\NowPlaying\NowPlaying;
use App\Events\TeamMember\PlayingTrack;
use App\Events\TeamMember\PlayingNothing;

class FetchCurrentTracksCommand extends Command
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
        'AdriaanMrn' => 'adriaan',
    ];

    public function handle()
    {
        $this->info('Fetching current tracks');

        $lastFm = new NowPlaying(config('services.last-fm.api_key'));

        collect($this->lastFmUsers)
            ->each(function (string $teamMemberName, string $lastFmUserName) use ($lastFm) {
                $currentTrack = $lastFm->getTrackInfo($lastFmUserName);

                $event = $currentTrack
                    ? new PlayingTrack($teamMemberName, $currentTrack)
                    : new PlayingNothing($teamMemberName);

                event($event);
            });

        $this->info('All done!');
    }
}
