<x-dashboard-tile :position="$position" refresh-interval="60">
    <h2 class="font-bold text-default">🏢 In Office</h2>
    <div class="mt-2 space-y-4">
        @foreach($days as $day)
            <div>
                @if($day['is_today'])
                    <h2 class="font-bold">Today:</h2>
                @else
                    <h3 class="text-dimmed text-sm capitalize">{{ $day['label'] }}</h3>
                @endif

                @if($day['is_holiday'] ?? false)
                    <span class="text-dimmed text-sm">🥳 <span class="italic">Public holiday</span></span>
                @else
                    @php
                        $count = count($day['in_office']);
                        $size = $day['is_today'] ? 2.5 : 2; // rem (w-10 / w-8)
                        $overlap = $count > 1 ? max(0.65, ($count * $size - 12) / ($count - 1)) : 0;
                    @endphp
                    <div class="flex mt-1" style="flex-wrap: nowrap;">
                        @forelse($day['in_office'] as $index => $person)
                            <div
                                class="relative shrink-0 rounded-full border-2 border-tile overflow-hidden {{ $day['is_today'] ? 'w-10 h-10' : 'w-8 h-8' }}"
                                style="{{ $index > 0 ? "margin-left: -{$overlap}rem;" : '' }}"
                                title="{{ $person['name'] }}"
                            >
                                @if($person['avatar'])
                                    <img
                                        src="{{ $person['avatar'] }}"
                                        alt="{{ $person['name'] }}"
                                        class="w-full h-full object-cover"
                                    />
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-dimmed bg-tile">
                                        {{ collect(explode(' ', $person['name']))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                                    </div>
                                @endif
                                @unless($day['is_today'])
                                    <div class="absolute inset-0 bg-tile opacity-50"></div>
                                @endunless
                            </div>
                        @empty
                            <span class="text-dimmed text-sm">—</span>
                        @endforelse
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-dashboard-tile>
