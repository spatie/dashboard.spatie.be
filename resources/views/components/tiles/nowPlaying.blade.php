<x-dashboard-tile :position="$position" refresh-interval="10">
    <div class="grid gap-4 h-full">
        @if($song)
            <div class="grid grid-cols-auto-1 gap-4 items-center">
                @if($song->album_art_url)
                    <div class="overflow-hidden w-16 h-16 rounded">
                        <img alt="album art" src="{{ $song->album_art_url }}" class="w-16 h-16 object-cover" />
                    </div>
                @endif

                <div class="leading-tight min-w-0">
                    <h2 class="truncate text-lg font-bold text-default">{{ $song->title }}</h2>
                    <p class="truncate text-dimmed">{{ $song->artist }}</p>
                    <p class="truncate text-sm text-dimmed">Requested by {{ $song->requested_by }}</p>
                </div>
            </div>

            @if($song->next_song_title)
                <div class="text-sm text-dimmed">
                    <span class="font-semibold">Up next:</span> {{ $song->next_song_title }} — {{ $song->next_song_artist }}
                </div>
            @endif
        @else
            <p class="text-dimmed">No song playing</p>
        @endif
    </div>
</x-dashboard-tile>
