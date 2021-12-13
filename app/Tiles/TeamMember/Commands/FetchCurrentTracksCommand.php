<?php

namespace App\Tiles\TeamMember\Commands;

use App\Tiles\TeamMember\MusicType;
use App\Tiles\TeamMember\TrackData;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Spatie\NowPlaying\NowPlaying;
use App\Tiles\TeamMember\TeamMemberStore;

class FetchCurrentTracksCommand extends Command
{
    protected $signature = 'dashboard:fetch-current-tracks';

    protected $description = 'Fetch the currently playing track from lastFm';

    public function getUsers()
    {
        return [
            'rias' => [
                'type' => MusicType::APPLE,
                'key' => config('services.apple-music.rias'),
            ],
            'freek' => [
                'type' => MusicType::APPLE,
                'key' => config('services.apple-music.freek'),
            ],
            'willem' => [
                'type' => MusicType::APPLE,
                'key' => config('services.apple-music.willem'),
            ],
            'sebastian' => [
                'type' => MusicType::LASTFM,
                'key' => 'sebdedeyne',
            ],
            'ruben' => [
                'type' => MusicType::LASTFM,
                'key' => 'ruben'
            ],
            'alex' => [
                'type' => MusicType::LASTFM,
                'key' => 'AlexVanderbist',
            ],
            'wouter' => [
                'type' => MusicType::LASTFM,
                'key' => 'wouterbrouwers',
            ],
            'brent' => [
                'type' => MusicType::LASTFM,
                'key' => 'brendt_gd',
            ],
            'adriaan' => [
                'type' => MusicType::LASTFM,
                'key' => 'AdriaanMrn',
            ],
        ];
    }

    public function handle()
    {
        $this->info('Fetching current tracks');

        collect($this->getUsers())->each(function ($config, $teamMemberName) {
            $teamMemberStore = TeamMemberStore::find($teamMemberName);

            if (! $config['key']) {
                return;
            }

            $trackData = match($config['type']) {
                MusicType::LASTFM => $this->fetchLastFm($config['key']),
                MusicType::APPLE => $this->fetchAppleMusic($config['key']),
                default => null,
            };

            if (! $trackData) {
                return;
            }

            $previousData = $teamMemberStore->nowPlaying();

            if ($previousData && $previousData->artist === $trackData->artist && $previousData->trackName === $trackData->trackName)
            {
                return;
            }

            $teamMemberStore->setNowPlaying($trackData);
            $teamMemberStore->setLastUpdate(now());
        });

        $this->info('All done!');
    }

    private function fetchLastFm(string $key): ?TrackData
    {
        $lastFm = new NowPlaying(config('services.last-fm.api_key'));

        try {
            $info = $lastFm->getTrackInfo($key);
            return $info
                ? new TrackData(...$info)
                : null;
        } catch (Exception $exception) {
            report($exception);
        }

        return null;
    }

    private function fetchAppleMusic(string $key): ?TrackData
    {
        $response = Http::withToken(config('services.apple-music.token'))
            ->withHeaders([
                'Music-User-Token' => $key,
            ])
            ->get('https://api.music.apple.com/v1/me/recent/played/tracks', [
                'limit' => 1,
                'types' => ['library-songs', 'songs'],
            ]);

        if (! $response->successful()) {
            return null;
        }

        $track = $response->json('data')[0] ?? null;

        if (! $track) {
            return null;
        }

        $durationInSeconds = $track['attributes']['durationInMillis'] / 1000;

        return new TrackData(
            artist: $track['attributes']['artistName'],
            album: $track['attributes']['albumName'],
            trackName: $track['attributes']['name'],
            url: $track['attributes']['url'],
            artwork: str_replace('{h}', $track['attributes']['artwork']['height'], str_replace('{w}', $track['attributes']['artwork']['width'], $track['attributes']['artwork']['url'])),
            durationInSeconds: $durationInSeconds,
        );
    }
}
