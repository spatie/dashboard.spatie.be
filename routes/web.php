<?php

use App\Http\Middleware\AccessToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::view('apple-music-token', 'apple-music-token');

Route::middleware(AccessToken::class)->group(function () {
    Route::get('/', function () {
        $birthdays = [
            'alex' => '1996-02-05',
            'freek' => '1979-09-22',
            'rias' => '1992-05-25',
            'ruben' => '1994-05-16',
            'sebastian' => '1992-02-01',
            'seb' => '1992-02-01',
            'jef' => '1975-03-28',
            'niels' => '1993-07-14',
            'sam' => '1998-05-01',
            'tim' => '1989-10-14',
            'wouter' => '1991-03-15',
        ];

        $members = collect(cache()->remember('members', now()->addDay(), function () {
            return Http::get('https://spatie.be/api/members')->json();
        }))->map(function (array $member) use ($birthdays) {
            $member['birthday'] = $birthdays[strtolower($member['name'])] ?? '';

            return $member;
        });


        return view('dashboard', compact('members'));
    });
});

Route::ohDearWebhooks('/oh-dear-webhooks');
