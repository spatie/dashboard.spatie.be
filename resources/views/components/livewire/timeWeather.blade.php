<x-tile :position="$position" no-fade>
    <div class="grid gap-2 justify-items-center h-full" style="grid-template-rows: auto 1fr auto;">
        {{--
        <div class="markup">
            <h1 x-data="currentTime()" x-init="() => { updateTime() }" x-text="time()"></h1>
        </div>
--}}

        <div class="grid gap-2 justify-items-center h-full" style="grid-template-rows: auto 1fr auto;">
            <div x-data="currentTime()" x-init="() => { updateTime() }">
                <div class="markup">
                    <h1 x-text="time()"></h1>
                </div>
                <div class="align-self-center font-bold text-4xl tracking-wide leading-none" x-text="date()"></div>
            </div>
            <div class="uppercase">
                <div class="grid gap-4 items-center" style="grid-template-columns: repeat(3, auto);">
                    <span> TEMPERATURE° <span class="text-sm uppercase text-dimmed">out</span> </span>
                    <span>
                        <office-temperature/>
                        <span class="text-sm uppercase text-dimmed">in</span>
                    </span>
                    @foreach($weatherIcons ?? [] as $icon)
                        <span class="text-2xl">{{ $icon }}</span>
                    @endforeach
                </div>
                <div class="hidden">WEATHER CITY</div>
            </div>
        </div>
        <div
            class="absolute pin-b pin-l w-full grid items-end"
            style="
                height: calc(1.25 * var(--tile-padding));
                grid-gap: 1px;
                grid-template-columns: repeat(12, 1fr);
                opacity: .15"
        >

            @foreach($rainForecasts ?? [] as $rainForecast)
                <div
                    class="rounded-sm bg-accent"
                    style="height:{{ $rainForecast['rain'] * 100 }}%"
                />
            @endforeach
        </div>
    </div>

    <script>
        function currentTime() {
            return {
                dateInstance: new Date,
                updateTime: function () {
                    let self = this;

                    setInterval(() => {
                        self.dateInstance = new Date;
                    }, 1000);
                },
                date: function () {
                    const day = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(this.dateInstance)
                    const month = new Intl.DateTimeFormat('en', { month: 'numeric' }).format(this.dateInstance)
                    const year = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(this.dateInstance)

                    return `${day}.${month}.${year}`
                },
                time: function () {
                    return `${this.dateInstance.getHours()}:${this.dateInstance.getMinutes()}:${this.dateInstance.getSeconds()}`
                }
            }
        }
    </script>

</x-tile>
