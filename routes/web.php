<?php

use App\Support\Weekplanning;
use App\Http\Middleware\AccessToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::view('apple-music-token', 'apple-music-token');

Route::middleware(AccessToken::class)->group(function () {
    Route::get('/', function (Weekplanning $weekplanning) {
        $showWeekplanning = $weekplanning->isActive();

        $members = collect();

        if (! $showWeekplanning) {
            $members = collect(cache()->remember('members', now()->addDay(), function () {
                return Http::withToken(config('services.spatie.token'))
                    ->get('https://spatie.be/api/members')
                    ->json();
            }));
        }

        return view('dashboard', [
            'members' => $members,
            'showWeekplanning' => $showWeekplanning,
            'weekplanningReloadInMilliseconds' => $weekplanning->millisecondsUntilNextTransition(),
        ]);
    });
});

Route::ohDearWebhooks('/oh-dear-webhooks');

Route::webhooks('/webhooks/now-playing');
