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
        const screens = [...document.querySelectorAll('[data-dashboard-screen]')];

        if (screens.length === 0) {
            return;
        }

        let activeScreenIndex = 0;
        let timeoutId = null;
        let liveIntervalId = null;
        let previousScreenName = null;

        function showScreen(screenIndex) {
            const activeScreen = screens[screenIndex];
            const screenName = activeScreen.dataset.screenName;

            if (previousScreenName !== null && previousScreenName !== screenName) {
                Livewire.dispatch('dashboard-screen-deactivated', { screenName: previousScreenName });
            }

            screens.forEach(function (screen, currentScreenIndex) {
                const isActiveScreen = currentScreenIndex === screenIndex;

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

            if (screens.length > 1) {
                const durationInSeconds = Number.parseInt(activeScreen.dataset.durationInSeconds, 10);

                timeoutId = window.setTimeout(function () {
                    activeScreenIndex = (screenIndex + 1) % screens.length;

                    showScreen(activeScreenIndex);
                }, durationInSeconds * 1000);
            }
        }

        showScreen(activeScreenIndex);
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

        @foreach($screens as $screenIndex => $screen)
            <section
                data-dashboard-screen
                data-screen-name="{{ $screen['name'] ?? 'screen-'.$screenIndex }}"
                data-duration-in-seconds="{{ $screen['duration_in_seconds'] }}"
                @if(($screen['view'] ?? null) === 'dashboard.screens.productAnalytics')
                    data-product-analytics-screen
                @endif
                @if(isset($screen['grid_columns'], $screen['grid_rows']))
                    style="grid-template-columns: repeat({{ $screen['grid_columns'] }}, minmax(0, 1fr)); grid-template-rows: repeat({{ $screen['grid_rows'] }}, minmax(0, 1fr));"
                @endif
                class="absolute inset-0 grid gap-2 transition-opacity duration-700 {{ $screen['type'] === 'view' ? 'p-2' : '' }} {{ $screenIndex === 0 ? '' : 'opacity-0 pointer-events-none' }}"
                @if($screenIndex !== 0)
                    aria-hidden="true"
                @endif
            >
                @if($screen['type'] === 'view')
                    @include($screen['view'])
                @else
                    <iframe
                        class="h-full w-full border-0 bg-canvas"
                        src="{{ $screen['url'] }}"
                        title="{{ $screen['name'] ?? 'Dashboard screen' }}"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                @endif
            </section>
        @endforeach
    @endif
</x-dashboard>
