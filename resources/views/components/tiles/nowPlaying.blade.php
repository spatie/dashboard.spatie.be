<x-dashboard-tile :position="$position" refresh-interval="10">
    <div class="grid gap-4 h-full">
        @if($song)
            <div class="flex items-center">
                @if($song->album_art_url)
                    <div class="overflow-hidden w-32 h-32 rounded">
                        <img alt="album art" src="{{ $song->album_art_url }}" class="w-32 h-32 object-cover" />
                    </div>
                @endif
            </div>

            <div class="leading-tight min-w-0">
                <h2 class="truncate text-lg font-bold text-default">{{ $song->title }}</h2>
                <p class="truncate text-dimmed mb-2">{{ $song->artist }}</p>
                <p class="truncate text-xs text-dimmed">
                   Requested by {{ $song->requested_by ?? 'Paolo' }}
                </p>
            </div>

            @if($song->next_song_title)
                <div class="text-sm text-dimmed">
                    <span class="font-semibold">Up next:</span> {{ $song->next_song_title }} — {{ $song->next_song_artist }}
                </div>
            @endif
        @else
            <div class="flex flex-col items-center justify-center h-full text-center">
                <p class="text-dimmed">No song playing</p>
                <p class="text-dimmed text-sm mt-2">Start playing at <span class="text-blue-400">liveat.spatie.be</span></p>
            </div>
        @endif
    </div>
</x-dashboard-tile>
