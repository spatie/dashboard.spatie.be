<?php

namespace App\Jobs;

use App\Models\NowPlayingSong;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessNowPlayingSongWebhookJob extends ProcessWebhookJob
{
    public function handle(): void
    {
        $payload = $this->webhookCall->payload;

        if (($payload['type'] ?? null) === 'artwork') {
            $artwork = Validator::make($payload, [
                'source_song_id' => ['required', 'integer', 'min:1'],
                'album_art_url' => ['required', 'url', 'starts_with:https://'],
            ])->validate();

            Cache::put('now-playing-artwork:'.$artwork['source_song_id'], $artwork['album_art_url'], now()->addMinutes(10));

            NowPlayingSong::query()
                ->where('source_song_id', $artwork['source_song_id'])
                ->where('playback_source', 'external')
                ->where('updated_at', '>=', now()->subMinutes(10))
                ->update(['album_art_url' => $artwork['album_art_url']]);

            return;
        }

        $validated = Validator::make($payload, [
            'title' => ['required', 'string'],
            'artist' => ['required', 'string'],
            'requested_by' => ['nullable', 'string'],
            'next_song_title' => ['nullable', 'string'],
            'next_song_artist' => ['nullable', 'string'],
            'album_art_url' => ['nullable', 'string', 'url'],
            'playback_source' => ['sometimes', 'in:owntone,external'],
            'source_song_id' => ['nullable', 'integer', 'min:1'],
        ])->validate();

        $validated['playback_source'] = $validated['playback_source'] ?? 'owntone';
        $validated['requested_by'] = $validated['playback_source'] === 'external' ? null : ($validated['requested_by'] ?? 'Paolo');
        $validated['source_song_id'] = $validated['playback_source'] === 'external' ? ($validated['source_song_id'] ?? null) : null;
        if ($validated['source_song_id'] && empty($validated['album_art_url'])) {
            $validated['album_art_url'] = Cache::get('now-playing-artwork:'.$validated['source_song_id']);
        }

        NowPlayingSong::query()->truncate();

        NowPlayingSong::create($validated);
    }
}
