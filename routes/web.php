<?php

use App\Support\Weekplanning;
use App\Http\Middleware\AccessToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::view('apple-music-token', 'apple-music-token');

Route::middleware(AccessToken::class)->group(function () {
    Route::get('/', function (Weekplanning $weekplanning) {
        $showWeekplanning = $weekplanning->isActive();
        $configuredScreens = config('dashboard.screens.items') ?: [
            [
                'name' => 'main',
                'view' => 'dashboard.screens.main',
                'duration_in_seconds' => config('dashboard.screens.default_duration_in_seconds', 60),
            ],
        ];

        $screens = collect($configuredScreens)
            ->filter(fn (mixed $screen) => is_array($screen))
            ->map(function (array $screen): array {
                $durationInSeconds = (int) (
                    $screen['duration_in_seconds']
                    ?? config('dashboard.screens.default_duration_in_seconds', 60)
                );
                $view = $screen['view'] ?? null;
                $url = $screen['url'] ?? null;

                $screen = [
                    ...$screen,
                    'duration_in_seconds' => max(1, $durationInSeconds),
                    'type' => null,
                ];

                if (is_string($view) && View::exists($view)) {
                    return [
                        ...$screen,
                        'type' => 'view',
                    ];
                }

                if (filled($url)) {
                    return [
                        ...$screen,
                        'type' => 'url',
                    ];
                }

                return $screen;
            })
            ->filter(fn (array $screen) => filled($screen['type']))
            ->values();

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
            'members' => $members,
            'screens' => $screens,
            'showWeekplanning' => $showWeekplanning,
            'weekplanningReloadInMilliseconds' => $weekplanning->millisecondsUntilNextTransition(),
        ]);
    });
});

Route::ohDearWebhooks('/oh-dear-webhooks');

Route::webhooks('/webhooks/now-playing');

Route::webhooks('/webhooks/oh-dear', 'oh-dear');
