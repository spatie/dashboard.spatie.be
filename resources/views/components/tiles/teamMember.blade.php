<x-dashboard-tile :position="$position">
    <div
        wire:poll.5s
        class="grid gap-2 h-full"
        style="grid-template-columns: 100%"
        style="{{ count($tasks) ? 'grid-template-rows: auto 1fr' : 'grid-template-rows: 1fr' }}"
    >
        <div class="self-start grid gap-2 items-center w-full bg-tile z-10" style="grid-template-columns: auto 1fr">
            @if($artwork)
                <div class="overflow-hidden w-10 h-10 rounded border border-canvas">
                    <span>
                        <img alt="artwork" src="{{ $artwork }}" class="w-10 h-10"/>
                    </span>
                </div>
            @else
                <div class="relative">
                    <div class="flex-none overflow-hidden w-10 h-10 rounded-full relative">
                        <img
                            src="{{ $avatar }}"
                            class="filter-gray block w-10 h-10"
                            style="object-fit: cover; filter: contrast(75%) grayscale(1) brightness(150%);"
                        />
                        <div class="absolute inset-0 bg-accent opacity-25"></div>
                    </div>
                    @if($isBirthday)
                        <div
                            class="absolute flex items-center justify-center text-xl"
                            style="top: -1.1rem; right: .05rem; transform:rotate(10deg);"
                        >👑</div>
                    @endif
                </div>
            @endif

            <div class="leading-tight min-w-0">
                <h2 class="truncate font-semibold capitalize">
                    {{ $name }}

                    @if($statusEmoji)
                        <span class="text-xl">{{ $statusEmoji }}</span>
                    @endif
                </h2>
                @if($currentTrack)
                    <span class="truncate text-sm block">
                    <span>🎵</span>
                    {{ $currentTrack }}
                </span>
                @endif
            </div>
        </div>

        @if(count($tasks))
            <div class="align-self-center">
                <ul>
                    @foreach($tasks['long'] as $task)
                        <li class="flex justify-between border-b-2 border-canvas py-1 leading-tight">
                            <div class="truncate">
                                <p class="truncate">
                                    {{ $task['project'] }}
                                </p>
                                @if($task['name'])
                                    <p class="flex-1 truncate text-xs text-dimmed">
                                        {{ ucfirst($task['name']) }}
                                    </p>
                                @endif
                            </div>
                            <p class="ml-2 font-semibold tabular-nums">
                                {{ $task['formatted_time'] }}
                            </p>
                        </li>
                    @endforeach
                </ul>
                <ul class="text-xs pt-1">
                    @foreach($tasks['short'] as $task)
                        <li class="flex justify-between">
                            <p class="truncate">
                                {{ $task['project'] }}

                                @if($task['name'])
                                    <span class="text-dimmed">{{ strtolower($task['name']) }}</span>
                                @endif
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

</x-dashboard-tile>
