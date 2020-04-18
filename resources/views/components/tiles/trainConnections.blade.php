<div wire:poll.5s>
    @if ($showTrains)
        <x-dashboard-tile :position="$position">
            <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;">
                <div class="flex">
                    <div class="grid place-center w-10 h-10 rounded-full"
                         style="background-color: rgba(255, 255, 255, .9)">
                        <div class="text-3xl leading-none -mt-1">🚃</div>
                    </div>
                    <h1 class="ml-2"></h1>
                </div>
                <div class="align-self-center grid gap-8" style="grid-auto-rows: auto;">
                    @foreach($trainConnections as $trainConnection)
                        <div>
                            <h2 class="uppercase">{{ $trainConnection['label'] }}</h2>
                            <ul class="mt-padding">
                                @foreach($trainConnection['trains'] as $train)
                                    @if($loop->iteration <= 3)
                                        <li
                                            class="{{ $train['canceled'] ? 'line-through text-danger' : '' }}"
                                        >
                                            <span class="mr-2">{{ $train['station'] }}</span>

                                            @if(! $train['canceled'] && $train['delay'] > 0)
                                                <span class="ml-auto mr-2 font-bold variant-tabular text-danger">{{ $train['delay'] }}m</span>
                                            @endif
                                            <span
                                                class="flex-none font-bold text-right variant-tabular">{{ \Carbon\Carbon::createFromTimestamp($train['time'])->format('H:i') }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-dashboard-tile>
    @endif
</div>
