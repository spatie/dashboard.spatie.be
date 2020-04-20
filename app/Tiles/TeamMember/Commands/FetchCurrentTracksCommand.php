<?php

namespace App\Tiles\TeamMember\Commands;

use App\Tiles\TeamMember\TeamMemberStore;
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
        'sebdedeyne' => 'sebastian',
        'ruben-va' => 'ruben',
        'AlexVanderbist' => 'alex',
        'wouterbrouwers' => 'wouter',
        'brendt_gd' => 'brent',
        'AdriaanMrn' => 'adriaan',
        'riasvdv' => 'rias',
    ];

    public function handle()
    {
        $this->info('Fetching current tracks');

        $lastFm = new NowPlaying(config('services.last-fm.api_key'));

        collect($this->lastFmUsers)
            ->each(function (string $teamMemberName, string $lastFmUserName) use ($lastFm) {
                $teamMemberStore = TeamMemberStore::find($teamMemberName);

                $currentTrack = $lastFm->getTrackInfo($lastFmUserName);

                $currentTrack
                    ? $teamMemberStore->setNowPlaying($currentTrack)
                    : $teamMemberStore->setNothingPlaying();
            });

        $this->info('All done!');
    }
}
