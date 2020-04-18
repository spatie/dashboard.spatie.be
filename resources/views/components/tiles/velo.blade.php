<x-dashboard-tile :position="$position">
    <div wire:poll.5s class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;">
        <div class="grid place-center w-10 h-10 rounded-full" style="background-color: rgba(255, 255, 255, .9)">
            <div class="text-3xl leading-none -mt-1">🚲</div>
        </div>
        <ul class="align-self-center">
            @foreach($stations as $station)
                <li>
                    <span class="{{ $station->displayClass() }}">
                        {{ $station->shortName() }}
                    </span>
                    <span>
                        <span class="{{ $station->isNearlyEmpty() ? $stations->displayClass() : '' }}"
                              class="font-bold variant-tabular">
                            {{ $station->numberOfBikesAvailable() }}
                        </span>
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</x-dashboard-tile>
