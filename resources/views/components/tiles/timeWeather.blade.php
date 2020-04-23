<x-dashboard-tile :position="$position">
    <div
        class="grid gap-2 justify-items-center h-full text-center"
        style="grid-template-rows: auto 1fr auto;"
        x-data="clock()"
        x-init="tick"
    >
        <h1 class="font-medium text-dimmed text-sm uppercase tracking-wide tabular-nums" x-text="date"></h1>

        <div class="self-center font-semibold text-4xl tracking-wide leading-none" x-text="time"></div>

        <div wire:poll.5s class="uppercase">
            <div class="flex w-full justify-center space-x-4 items-center">
                <span> {{ $outsideTemperature }}° <span class="text-sm uppercase text-dimmed">out</span> </span>

                @if($insideTemperature)
                    <span>
                        <span>{{ $insideTemperature }}°</span>
                        <span class="text-sm uppercase text-dimmed">in</span>
                    </span>
                @endif

                <span class="text-2xl">{{ $emoji }}</span>
            </div>
            <div class="hidden">{{ $city }}</div>
        </div>

        <div
            wire:poll.5s
            class="absolute bottom-0 left-0 w-full grid items-end"
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
                dateTime: new Date(),

                tick() {
                    setInterval(() => {
                        this.dateTime = new Date();
                    }, 1000);
                },

                get date() {
                    const day = this.dateTime
                        .toLocaleDateString('en-US', { weekday: 'long' })
                        .substr(0, 3);

                    const date = [
                        this.dateTime.getDate(),
                        this.dateTime.getMonth() + 1,
                    ].map(this.padNumber).join('/');

                    return `${day} ${date}`;
                },

                get time() {
                    return [
                        this.dateTime.getHours(),
                        this.dateTime.getMinutes(),
                    ].map(this.padNumber).join(':');
                },

                padNumber(number) {
                    return String(number).padStart(2, '0');
                }
            }
        }
    </script>

</x-dashboard-tile>
