@push('assets')
    @vite('resources/css/app.css')
@endpush
@push('scripts')
<script>
    window.setTimeout(function () {
        window.location.reload();
    }, {{ $weekplanningReloadInMilliseconds }});

    document.addEventListener('livewire:init', function () {
        Livewire.on('deploy-detected', function () {
            window.location.reload();
        });
    });

    document.addEventListener('livewire:initialized', function () {
        const schedule = @js($schedule);
        let availableScreenNames = new Set(@js($availableScreenNames));
        const screens = new Map(
            [...document.querySelectorAll('[data-dashboard-screen]')]
                .map(screen => [screen.dataset.screenName, screen])
        );

        if (screens.size === 0 || schedule.length === 0) {
            return;
        }

        let activeScheduleIndex = 0;
        let timeoutId = null;
        let liveIntervalId = null;
        let previousScreenName = null;

        function findAvailableScheduleIndex(scheduleIndex) {
            for (let offset = 0; offset < schedule.length; offset++) {
                const candidateScheduleIndex = (scheduleIndex + offset) % schedule.length;
                const candidateScreenName = schedule[candidateScheduleIndex].screen;

                if (availableScreenNames.has(candidateScreenName)) {
                    return candidateScheduleIndex;
                }
            }

            return null;
        }

        function showScheduledScreen(scheduleIndex) {
            const availableScheduleIndex = findAvailableScheduleIndex(scheduleIndex);

            if (availableScheduleIndex === null) {
                return;
            }

            const {
                screen: screenName,
                duration_in_seconds: durationInSeconds,
            } = schedule[availableScheduleIndex];
            const activeScreen = screens.get(screenName);

            activeScheduleIndex = availableScheduleIndex;

            if (previousScreenName !== null && previousScreenName !== screenName) {
                Livewire.dispatch('dashboard-screen-deactivated', { screenName: previousScreenName });
            }

            screens.forEach(function (screen) {
                const isActiveScreen = screen === activeScreen;

                screen.classList.toggle('opacity-0', ! isActiveScreen);
                screen.classList.toggle('pointer-events-none', ! isActiveScreen);
                screen.toggleAttribute('aria-hidden', ! isActiveScreen);
            });

            Livewire.dispatch('dashboard-screen-activated', { screenName });

            previousScreenName = screenName;

            window.clearTimeout(timeoutId);
            window.clearInterval(liveIntervalId);

            if (activeScreen.hasAttribute('data-product-analytics-screen')) {
                liveIntervalId = window.setInterval(function () {
                    Livewire.dispatch('dashboard-product-analytics-live-refresh', { screenName });
                }, 30_000);
            }

            if (schedule.length > 1) {
                timeoutId = window.setTimeout(function () {
                    activeScheduleIndex = (availableScheduleIndex + 1) % schedule.length;

                    showScheduledScreen(activeScheduleIndex);
                }, durationInSeconds * 1000);
            }
        }

        Livewire.on('dashboard-screen-availability-updated', function ({ availableScreenNames: screenNames }) {
            availableScreenNames = new Set(screenNames);

            const activeScreenName = schedule[activeScheduleIndex].screen;

            if (availableScreenNames.has(activeScreenName)) {
                return;
            }

            showScheduledScreen((activeScheduleIndex + 1) % schedule.length);
        });

        showScheduledScreen(activeScheduleIndex);
    });
</script>
@endpush
<x-dashboard>
    @if($showWeekplanning)
        <div class="fixed inset-0 z-10 p-10 flex items-center justify-center bg-canvas px-8 text-center text-[60px] font-black tracking-tight text-default">
            Weekplanning!
        </div>
    @else
        <livewire:deploy-checker />

        @if($hasConditionalScreens)
            <livewire:screen-condition-checker :screen-names="$screens->keys()->all()" />
        @endif

        @foreach($screens as $screenName => $screen)
            <section
                data-dashboard-screen
                data-screen-name="{{ $screenName }}"
                @if(($screen['view'] ?? null) === 'dashboard.screens.productAnalytics')
                    data-product-analytics-screen
                @endif
                @if(isset($screen['grid_columns'], $screen['grid_rows']))
                    style="grid-template-columns: repeat({{ $screen['grid_columns'] }}, minmax(0, 1fr)); grid-template-rows: repeat({{ $screen['grid_rows'] }}, minmax(0, 1fr));"
                @endif
                class="absolute inset-0 grid gap-2 transition-opacity duration-700 {{ $screen['type'] === 'view' ? 'p-2' : '' }} {{ $screenName === $initialScreenName ? '' : 'opacity-0 pointer-events-none' }}"
                @if($screenName !== $initialScreenName)
                    aria-hidden="true"
                @endif
            >
                @if($screen['type'] === 'view')
                    @include($screen['view'])
                @else
                    <iframe
                        class="h-full w-full border-0 bg-canvas"
                        src="{{ $screen['url'] }}"
                        title="{{ str($screenName)->headline() }}"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                @endif
            </section>
        @endforeach
    @endif
</x-dashboard>
