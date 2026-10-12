<x-dashboard-tile :position="$position" :show="$showTile" :refresh-interval="$refreshIntervalInSeconds">
    <div class="grid grid-rows-auto-1 gap-2 h-full">
        <div
            class="flex items-center justify-center w-10 h-10 rounded-full"
            style="background-color: rgba(255, 255, 255, .9)"
        >
            <div class="text-3xl leading-none -mt-1">
                🚃
            </div>
        </div>
        <div class="self-center | grid gap-8" style="grid-auto-rows: auto;">
            @foreach($trainConnections as $trainConnection)
                <div class="grid gap-2">
                    <h2 class="uppercase">
                        {{ $trainConnection['label'] }}
                    </h2>
                    <ul class="divide-y-2">
                        @foreach($trainConnection['trains'] as $train)
                            @if($loop->iteration <= 3)
                                <li class="
                                    grid grid-cols-1-auto-auto py-1
                                    {{ $train['canceled'] ? 'line-through text-danger' : '' }}
                                ">
                                    <span class="mr-2">
                                        {{ trim($train['station']) === 'Breda (NL) & Rotterdam Centraal (NL)' ? 'Rotterdam' : $train['station'] }}
                                    </span>

                                    @if(! $train['canceled'] && $train['delay'] > 0)
                                        <span class="ml-auto mr-2 font-bold variant-tabular text-danger">
                                            {{ $train['delay'] }}m
                                        </span>
                                    @endif

                                    <span class="flex-none font-bold text-right variant-tabular">
                                        {{ \Carbon\Carbon::createFromTimestamp($train['time'], config('app.timezone'))->format('H:i') }}
                                    </span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</x-dashboard-tile>
