<x-dashboard-tile :position="$position" refresh-interval="30">
    <h2>{{ $label }}</h2>
    <div class="grid gap-2 h-full">
        <ul class="mt-4 divide-y-2">
            <li class="py-1 grid grid-cols-1-auto">
                <span>Current</span>
                <span class="font-bold tabular-nums">
                    {{ $current }}
                </span>
            </li>
            <li class="py-1 grid grid-cols-1-auto">
                <span>Visitors</span>
                <span class="font-bold tabular-nums">
                    {{ $visitors }}
                </span>
            </li>
            <li class="py-1 grid grid-cols-1-auto">
                <span>Views</span>
                <span class="font-bold tabular-nums">
                    {{ $views }}
                </span>
            </li>
            <li class="py-1 grid grid-cols-1-auto">
                <span>Avg time</span>
                <span class="font-bold tabular-nums">
                    {{ $avgTimeOnSite }}
                </span>
            </li>
            <li class="py-1 grid grid-cols-1-auto">
                <span>Bounce rate</span>
                <span class="font-bold tabular-nums">
                    {{ $bounceRate }}
                </span>
            </li>
            @foreach ($eventCompletions as $eventCompletion)
            <li class="py-1 grid grid-cols-1-auto">
                <span>{{ $eventCompletion['name'] }}</span>
                <span class="font-bold tabular-nums">
                    {{ $eventCompletion['completions'] }}
                </span>
            </li>
            @endforeach
        </ul>
    </div>
</x-dashboard-tile>
