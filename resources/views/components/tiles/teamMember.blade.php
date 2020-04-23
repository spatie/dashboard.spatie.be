<x-dashboard-tile :position="$position">
    <div wire:poll.5s class="grid {{ $hasTasks ? 'grid-rows-auto-1' : 'grid-rows-1' }} gap-2 h-full">
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

                @if($currentTrack)
                    <p class="truncate text-sm">
                        🎵 {{ $currentTrack }}
                    </p>
                @endif
            </div>
        </div>

        @if($hasTasks)
            <div class="self-center">
                <ul>
                    @foreach($tasks['long'] as $task)
                        <li class="grid grid-cols-1-auto py-1 border-b-2">
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

                            <p class="ml-2 font-bold tabular-nums">
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
                                    <span class="text-dimmed">
                                        {{ strtolower($task['name']) }}
                                    </span>
                                @endif
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-dashboard-tile>
