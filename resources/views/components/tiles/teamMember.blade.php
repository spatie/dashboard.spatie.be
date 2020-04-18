<x-tile :position="$position">
    {{ ucfirst($nickName ?? $name) }}
    <div wire:poll.5s
        class="grid gap-padding h-full markup"
        style="grid-template-columns: 100%"
        style="{{ count($tasks) ? 'grid-template-rows: auto 1fr' : 'grid-template-rows: 1fr' }}"
    >

        <div class="grid gap-2 items-center w-full bg-tile z-10" style="grid-template-columns: auto 1fr">
            @if($artwork)
                <div class="overflow-hidden w-10 h-10 rounded border border-screen">
                    <span>
                        <img alt="artwork" src="{{ $artwork }}" class="w-10 h-10"/>
                    </span>
                </div>
            @else
                <div class="flex-none overflow-hidden w-10 h-10 rounded-full">
                    <img class="filter-grey block w-10 h-10" src="{{ $avatar }}" style="object-fit: cover;"/>
                    <div class="absolute pin bg-accent opacity-25"></div>
                </div>
                @if($isBirthday)
                    <div
                        class="absolute flex items-center justify-center text-3xl"
                        style="top: -1rem; right: .05rem; transform:rotate(7deg);"
                    />👑
                @endif
            @endif
        </div>

        <div class="leading-tight min-w-0">
            <h2 class="truncate capitalize">
                @if($statusEmoji)
                    <span class="text-xl">{{ $statusEmoji }}</span>
                @endif
            </h2>
            @if($currentTrack)
                <span class="truncate text-sm block">
                <span>🎵</span>
                {{ $currentTrack }}
            </span>
            @endif($currentTrack)
        </div>

        @if(count($tasks))
            <div class="align-self-center">
                <ul>
                    @foreach($tasks['long'] as $task)
                        <li>
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
                            <p class="ml-2 font-bold variant-tabular">{{ $task['formatted_time'] }}</p>
                        </li>
                    @endforeach
                </ul>
                <ul class="text-xs border-t-2 border-screen pt-1">
                    @foreach($tasks['short'] as $task)
                        <li>
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

</x-tile>
