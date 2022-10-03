<x-dashboard-tile :position="$position" refresh-interval="30">
    <h2>{{ $label }}</h2>
    <div class="grid gap-2 h-full">
        <ul class="self-center divide-y-2">
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
                <span>Avg time on site</span>
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
            <li class="py-1 grid grid-cols-1-auto">
                <span>Event completions</span>
                <span class="font-bold tabular-nums">
                    {{ $eventCompletions }}
                </span>
            </li>
        </ul>
    </div>
</x-dashboard-tile>
