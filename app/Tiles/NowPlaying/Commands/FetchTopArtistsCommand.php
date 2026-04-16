<?php

namespace App\Tiles\NowPlaying\Commands;

use App\Tiles\NowPlaying\NowPlayingStore;
use App\Tiles\NowPlaying\TopArtistData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchTopArtistsCommand extends Command
{
    protected $signature = 'dashboard:fetch-top-artists';

    protected $description = 'Fetch the top artists of the week from the liveat.spatie.be media server';

    public function handle(): void
    {
        $this->info('Fetching top artists...');

        $response = Http::get('https://liveat.spatie.be/api/stats/top-artists');

        if (! $response->successful()) {
            $this->error("Failed to fetch top artists: ({$response->status()}) {$response->body()}");

            return;
        }

        $artists = collect($response->json('data', []))
            ->map(fn (array $artist) => new TopArtistData(
                artist: $artist['artist'],
                plays: $artist['plays'],
                artworkUrl: $artist['artworkUrl'] ?? null,
            ))
            ->all();

        NowPlayingStore::make()->setTopArtists($artists);

        $this->info('All done!');
    }
}
