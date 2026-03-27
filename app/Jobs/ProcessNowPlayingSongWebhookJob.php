<?php

namespace App\Jobs;

use App\Models\NowPlayingSong;
use Illuminate\Support\Facades\Validator;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessNowPlayingSongWebhookJob extends ProcessWebhookJob
{
    public function handle(): void
    {
        $payload = $this->webhookCall->payload;

        $validated = Validator::make($payload, [
            'title' => ['required', 'string'],
            'artist' => ['required', 'string'],
            'requested_by' => ['nullable', 'string'],
            'next_song_title' => ['required', 'string'],
            'next_song_artist' => ['required', 'string'],
            'album_art_url' => ['nullable', 'string', 'url'],
        ])->validate();

        $validated['requested_by'] = $validated['requested_by'] ?? 'Paolo';

        NowPlayingSong::query()->truncate();

        NowPlayingSong::create($validated);
    }
}
