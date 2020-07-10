<x-dashboard-tile :position="$position" refresh-interval="30">
    <div class="grid grid-rows-auto-1 gap-2 h-full">
        <div class="grid grid-cols-auto-1 gap-2 items-center | bg-tile">
            @if($artwork)
                <div class="overflow-hidden w-10 h-10 rounded border">
                    <img alt="artwork" src="{{ $artwork }}" class="w-10 h-10"/>
                </div>
            @else
                <div class="relative">
                    <div class="overflow-hidden w-10 h-10 rounded-full relative">
                        <img
                            src="{{ $avatar }}"
                            class="block w-10 h-10 object-cover filter-gray"
                            style="filter: contrast(75%) grayscale(1) brightness(150%)"
                        />
                        <div class="absolute inset-0 bg-accent opacity-25"></div>
                    </div>

                    @if($isBirthday)
                        <div
                            class="absolute text-xl"
                            style="
                                top: -1.1rem;
                                right: .05rem;
                                transform:rotate(10deg);
                            ">
                            👑
                        </div>
                    @endif
                </div>
            @endif

            <div class="leading-tight min-w-0">
                <h2 class="truncate font-bold capitalize">
                    {{ $name }}

                    @if($statusEmoji)
                        <span class="text-xl">{{ $statusEmoji }}</span>
                    @endif
                </h2>

                @if($currentArtist)
                    <p class="truncate text-sm">
                        🎵 {{ $currentArtist }}
                    </p>
                @endif
            </div>
        </div>


    </div>
</x-dashboard-tile>
