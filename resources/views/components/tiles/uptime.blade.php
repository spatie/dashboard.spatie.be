<x-dashboard-tile wire:poll.30s :position="$position" :show="count($downSites)">
    <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr" class="markup bg-warn">
        <h1>Downtime</h1>
        <ul class="align-self-center">
            @foreach($downSites as $downSite)
                <div class="font-bold truncate">{{ $downSite }}</div>
            @endforeach
        </ul>
    </div>
</x-dashboard-tile>
