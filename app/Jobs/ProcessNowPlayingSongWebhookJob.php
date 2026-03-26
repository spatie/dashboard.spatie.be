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
            'requested_by' => ['required', 'string'],
            'next_song_title' => ['required', 'string'],
            'next_song_artist' => ['required', 'string'],
            'album_art_url' => ['nullable', 'string', 'url'],
        ])->validate();

        NowPlayingSong::query()->truncate();

        NowPlayingSong::create($validated);
    }
}
