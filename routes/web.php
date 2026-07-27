<?php

use App\Models\Deploy;
use App\Support\Weekplanning;
use App\Dashboard\ScreenAvailability;
use App\Http\Middleware\AccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::view('apple-music-token', 'apple-music-token');

Route::middleware(AccessToken::class)->group(function () {
    Route::get('deploy-status', function (): JsonResponse {
        return response()
            ->json([
                'deployId' => Deploy::latest('id')->value('id') ?? 0,
            ])
            ->header('Cache-Control', 'no-store');
    })->name('deployStatus');

    Route::get('/', function (Weekplanning $weekplanning, ScreenAvailability $screenAvailability) {
        $showWeekplanning = $weekplanning->isActive();
        $defaultDurationInSeconds = (int) config('dashboard.default_duration_in_seconds', 60);

        $availableScreens = collect(config('dashboard.screens', []))
            ->filter(fn (mixed $screen, mixed $screenName) => is_string($screenName) && is_array($screen))
            ->map(function (array $screen, string $screenName): array {
                $view = $screen['view'] ?? null;
                $url = $screen['url'] ?? null;

                $screen = [
                    ...$screen,
                    'name' => $screenName,
                    'type' => null,
                ];

                if (is_string($view) && View::exists($view)) {
                    return [
                        ...$screen,
                        'type' => 'view',
                    ];
                }

                if (is_string($url) && filled($url)) {
                    return [
                        ...$screen,
                        'type' => 'url',
                    ];
                }

                return $screen;
            })
            ->filter(fn (array $screen) => filled($screen['type']))
            ->all();

        $schedule = collect(config('dashboard.schedule', []))
            ->filter(fn (mixed $scheduleEntry) => is_array($scheduleEntry))
            ->map(function (array $scheduleEntry) use ($availableScreens, $defaultDurationInSeconds): ?array {
                $screenName = $scheduleEntry['screen'] ?? null;

                if (! is_string($screenName)) {
                    return null;
                }

                if (! array_key_exists($screenName, $availableScreens)) {
                    return null;
                }

                $durationInSeconds = (int) ($scheduleEntry['duration_in_seconds'] ?? $defaultDurationInSeconds);

                return [
                    'screen' => $screenName,
                    'duration_in_seconds' => max(1, $durationInSeconds),
                ];
            })
            ->filter()
            ->values();

        if ($schedule->isEmpty()) {
            $availableScreens['main'] = $availableScreens['main'] ?? [
                'name' => 'main',
                'view' => 'dashboard.screens.main',
                'type' => 'view',
            ];

            $schedule = collect([
                [
                    'screen' => 'main',
                    'duration_in_seconds' => max(1, $defaultDurationInSeconds),
                ],
            ]);
        }

        $screens = $schedule
            ->pluck('screen')
            ->unique()
            ->mapWithKeys(fn (string $screenName): array => [$screenName => $availableScreens[$screenName]]);

        $availableScreenNames = $screenAvailability->availableScreenNames($screens->all());
        $initialScreenName = $schedule
            ->first(fn (array $scheduleEntry): bool => in_array(
                $scheduleEntry['screen'],
                $availableScreenNames,
                true,
            ))['screen'] ?? $screens->keys()->first();
        $hasConditionalScreens = $screens
            ->contains(fn (array $screen): bool => isset($screen['condition']));

        $members = collect();

        if (
            ! $showWeekplanning
            && $screens->contains(fn (array $screen) => ($screen['view'] ?? null) === 'dashboard.screens.main')
        ) {
            $members = collect(cache()->remember('members', now()->addDay(), function () {
                return Http::withToken(config('services.spatie.token'))
                    ->get('https://spatie.be/api/members')
                    ->json();
            }));
        }

        return view('dashboard', [
            'loadedDeployId' => Deploy::latest('id')->value('id') ?? 0,
            'members' => $members,
            'schedule' => $schedule,
            'screens' => $screens,
            'availableScreenNames' => $availableScreenNames,
            'initialScreenName' => $initialScreenName,
            'hasConditionalScreens' => $hasConditionalScreens,
            'showWeekplanning' => $showWeekplanning,
            'weekplanningReloadInMilliseconds' => $weekplanning->millisecondsUntilNextTransition(),
        ]);
    });
});

Route::ohDearWebhooks('/oh-dear-webhooks');

Route::webhooks('/webhooks/now-playing');

Route::webhooks('/webhooks/oh-dear', 'oh-dear');
