<x-dashboard-tile :position="$position">
    <div wire:poll.{{ $refreshInSeconds }}s class="grid gap-padding h-full markup">
        <ul class="align-self-center">
            @foreach($events as $event)
                <li>
                    <div class="my-2">
                        <div class="{{ $event['withinWeek'] ? 'font-bold' : '' }}">{{ $event['name'] }}</div>
                        <div class="text-sm text-dimmed">{{ $event['date']->format('d.m.Y') }}</div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-dashboard-tile>
