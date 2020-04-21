<x-dashboard-tile :position="$position">
    <div class="grid gap-2 justify-items-center h-full" style="grid-template-rows: auto 1fr auto;">
        <div class="grid gap-2 justify-items-center h-full" style="grid-template-rows: auto 1fr auto;">
            <div x-data="clock()" x-init="tick">
                <div class="markup">
                    <h1 x-text="time"></h1>
                </div>
                <div class="align-self-center font-bold text-4xl tracking-wide leading-none" x-text="date"></div>
            </div>
            <div class="uppercase" wire:poll.5s>
                <div class="grid gap-4 items-center" style="grid-template-columns: repeat(3, auto);">
                    <span> {{ $outsideTemperature }}° <span class="text-sm uppercase text-dimmed">out</span> </span>
                    <span>
                        @if($insideTemperature)
                            <span>{{ $insideTemperature }}°</span>
                        @endif
                        <span class="text-sm uppercase text-dimmed">in</span>
                    </span>

                    <span class="text-2xl">{{ $emoji }}</span>
                </div>
                <div class="hidden">{{ $city }}</div>
            </div>
        </div>
        <div wire:poll.5s
            class="absolute pin-b pin-l w-full grid items-end"
            style="
                height: calc(1.25 * var(--tile-padding));
                grid-gap: 1px;
                grid-template-columns: repeat(12, 1fr);
                opacity: .15"
        >

            @foreach($forecasts as $forecast)
                <div
                    class="rounded-sm bg-accent"
                    style="height:{{ $forecast['rain'] * 100 }}%"
                />
            @endforeach
        </div>
    </div>

    <script>
        function clock() {
            return {
                now: new Date(),

                tick() {
                    setInterval(() => {
                        this.now = new Date();
                    }, 1000);
                },

                get date() {
                    return [
                        this.now.getDate(),
                        this.now.getMonth() + 1,
                    ].map(this.padNumber).join(':');
                },

                get time() {
                    return [
                        this.now.getHours(),
                        this.now.getMinutes(),
                        this.now.getSeconds(),
                    ].map(this.padNumber).join(':');
                },

                padNumber(number) {
                    return String(number).padStart(2, '0');
                }
            }
        }
    </script>

</x-dashboard-tile>
