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

    document.addEventListener('DOMContentLoaded', function () {
        const screens = [...document.querySelectorAll('[data-dashboard-screen]')];

        if (screens.length <= 1) {
            return;
        }

        let activeScreenIndex = 0;
        let timeoutId = null;

        function showScreen(screenIndex) {
            screens.forEach(function (screen, currentScreenIndex) {
                const isActiveScreen = currentScreenIndex === screenIndex;

                screen.classList.toggle('opacity-0', ! isActiveScreen);
                screen.classList.toggle('pointer-events-none', ! isActiveScreen);
                screen.toggleAttribute('aria-hidden', ! isActiveScreen);
            });

            const durationInSeconds = Number.parseInt(screens[screenIndex].dataset.durationInSeconds, 10);

            window.clearTimeout(timeoutId);

            timeoutId = window.setTimeout(function () {
                activeScreenIndex = (screenIndex + 1) % screens.length;

                showScreen(activeScreenIndex);
            }, durationInSeconds * 1000);
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
                data-duration-in-seconds="{{ $screen['duration_in_seconds'] }}"
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
