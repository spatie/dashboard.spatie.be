<x-dashboard-tile :position="$position" refresh-interval="5">
    <div class="grid gap-2 h-full">
        <div
            class="flex items-center justify-center w-10 h-10 rounded-full"
            style="background-color: rgba(255, 255, 255, .9)"
        >
            <div class="text-3xl leading-none -mt-1">
                ☕
            </div>
        </div>
        <ul class="self-center divide-y-2">
            <li class="py-1 grid grid-cols-1-auto">
                <span>Today</span>
                <span class="font-bold tabular-nums">
                    {{ formatNaturalNumber($today) }}
                </span>
            </li>
            <li class="py-1 grid grid-cols-1-auto">
                <span>This week</span>
                <span class="font-bold tabular-nums">
                    {{ formatNaturalNumber($thisWeek) }}
                </span>
            </li>
            <li class="py-1 grid grid-cols-1-auto">
                <span>Total</span> <span class="font-bold tabular-nums">
                    {{ formatNaturalNumber($total) }}
                </span>
            </li>
        </ul>

        <div
            class="absolute bottom-0 left-0 h-full w-full grid items-end"
            style="
                grid-gap: 1px;
                grid-template-columns: repeat(14, 1fr);
                opacity: .15"
        >
            @foreach($days as $day)
                <div
                    class="rounded-sm bg-accent"
                    style="height:{{ $day / ($daysMax ?: 1) * 70 }}%"
                ></div>
            @endforeach
        </div>
    </div>
</x-dashboard-tile>
