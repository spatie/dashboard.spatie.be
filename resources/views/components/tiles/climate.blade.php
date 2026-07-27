<x-dashboard-tile :position="$position" refresh-interval="30">
    <div class="flex flex-col h-full">
        <h2 class="font-bold text-default">Climate</h2>

        <div class="grid grid-cols-2 gap-3 my-auto text-center">
            <div>
                <p class="text-xs uppercase tracking-wide text-dimmed">Indoor</p>
                <p class="mt-1 text-3xl font-bold text-default">
                    {{ $indoorTemperature === null ? '—' : number_format($indoorTemperature, 1) . '°' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wide text-dimmed">Outdoor</p>
                <p class="mt-1 text-3xl font-bold text-default">
                    {{ $outdoorTemperature === null ? '—' : number_format($outdoorTemperature, 1) . '°' }}
                </p>
            </div>
        </div>

        <div class="flex items-center justify-center gap-2 text-sm font-semibold {{ $acOn === true ? 'text-blue-400' : 'text-dimmed' }}">
            <span class="w-2 h-2 rounded-full {{ $acOn === true ? 'bg-blue-400' : 'bg-current opacity-50' }}"></span>
            <span>
                @if($acOn === null)
                    AC —
                @elseif($acOn)
                    AC on
                @else
                    AC off
                @endif
            </span>
        </div>
    </div>
</x-dashboard-tile>
