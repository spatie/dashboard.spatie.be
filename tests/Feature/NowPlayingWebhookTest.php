<?php

namespace Tests\Feature;

use App\Jobs\ProcessNowPlayingSongWebhookJob;
use App\Models\NowPlayingSong;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\WebhookClient\Models\WebhookCall;
use Tests\TestCase;

class NowPlayingWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function testArtworkArrivingAfterAnExternalSongUpdatesTheCurrentTile(): void
    {
        $this->processWebhook([
            'title' => 'Song',
            'artist' => 'Artist',
            'requested_by' => null,
            'next_song_title' => null,
            'next_song_artist' => null,
            'album_art_url' => null,
            'playback_source' => 'external',
            'source_song_id' => 123,
        ]);

        $this->assertSame('external', NowPlayingSong::current()->playback_source);
        $this->assertNull(NowPlayingSong::current()->requested_by);
        $this->assertNull(NowPlayingSong::current()->album_art_url);

        $this->processWebhook([
            'type' => 'artwork',
            'source_song_id' => 123,
            'album_art_url' => 'https://example.com/cover.jpg',
        ]);

        $this->assertSame(1, NowPlayingSong::query()->count());
        $this->assertSame('https://example.com/cover.jpg', NowPlayingSong::current()->album_art_url);

        $tile = view('components.tiles.nowPlaying', [
            'position' => 'a1',
            'song' => NowPlayingSong::current(),
            'topArtist' => null,
        ])->render();

        $this->assertStringContainsString('https://example.com/cover.jpg', $tile);
        $this->assertStringContainsString('External playback', $tile);
        $this->assertStringNotContainsString('Requested by Paolo', $tile);
    }

    public function testLateArtworkCannotReplaceAnotherSong(): void
    {
        NowPlayingSong::create([
            'title' => 'New song',
            'artist' => 'Artist',
            'playback_source' => 'external',
            'source_song_id' => 456,
        ]);

        $this->processWebhook([
            'type' => 'artwork',
            'source_song_id' => 123,
            'album_art_url' => 'https://example.com/old-cover.jpg',
        ]);

        $this->assertNull(NowPlayingSong::current()->album_art_url);
    }

    public function testArtworkArrivingBeforeTheSongWebhookIsRetained(): void
    {
        Cache::forget('now-playing-artwork:123');

        $this->processWebhook([
            'type' => 'artwork',
            'source_song_id' => 123,
            'album_art_url' => 'https://example.com/cover.jpg',
        ]);
        $this->processWebhook([
            'title' => 'Song',
            'artist' => 'Artist',
            'playback_source' => 'external',
            'source_song_id' => 123,
            'album_art_url' => null,
        ]);

        $this->assertSame('https://example.com/cover.jpg', NowPlayingSong::current()->album_art_url);
    }

    public function testExistingOwnToneWebhooksKeepTheirRequesterFallback(): void
    {
        $this->processWebhook([
            'title' => 'Queue song',
            'artist' => 'Artist',
            'requested_by' => null,
            'album_art_url' => 'https://example.com/queue-cover.jpg',
        ]);

        $this->assertSame('owntone', NowPlayingSong::current()->playback_source);
        $this->assertSame('Paolo', NowPlayingSong::current()->requested_by);
        $this->assertNull(NowPlayingSong::current()->source_song_id);
    }

    private function processWebhook(array $payload): void
    {
        (new ProcessNowPlayingSongWebhookJob(new WebhookCall(['payload' => $payload])))->handle();
    }
}
