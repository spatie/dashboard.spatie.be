<?php

use App\Http\Middleware\AccessToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::view('apple-music-token', 'apple-music-token');

Route::middleware(AccessToken::class)->group(function () {
    Route::get('/', function () {
        $members = collect(cache()->remember('members', now()->addDay(), function () {
            return Http::withToken(config('services.spatie.token'))
                ->get('https://spatie.be/api/members')
                ->json();
        }));

        return view('dashboard', compact('members'));
    });
});

Route::ohDearWebhooks('/oh-dear-webhooks');
