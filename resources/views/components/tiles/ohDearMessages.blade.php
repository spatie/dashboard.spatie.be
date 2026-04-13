<x-dashboard-tile :position="$position" refresh-interval="30">
    <div class="flex flex-col h-full gap-2 overflow-hidden">
        <h2 class="text-sm text-dimmed uppercase tracking-wide">😱 Oh Dear</h2>

        @if($groups->isEmpty())
            <div class="flex-1 flex items-center justify-center text-dimmed text-sm">
                No alerts in the last 24h
            </div>
        @else
            <div class="flex-1 flex flex-col gap-1 overflow-hidden">
                @foreach($groups as $group)
                    <div class="flex items-center gap-2 min-w-0">
                        <span @class([
                            'w-2 h-2 rounded-full shrink-0',
                            'bg-red-500' => $group['severity'] === 'error',
                            'bg-amber-400' => $group['severity'] === 'warning',
                        ])></span>
                        <div class="flex-1 min-w-0 leading-tight">
                            <p class="truncate text-default text-sm">{{ $group['title'] }}</p>
                            @if($group['site'])
                                <p class="truncate text-dimmed text-xs">{{ $group['site'] }}</p>
                            @endif
                        </div>
                        <span class="shrink-0 rounded bg-white/10 px-2 py-0.5 text-xs font-semibold text-default">
                            {{ $group['count'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-dashboard-tile>
