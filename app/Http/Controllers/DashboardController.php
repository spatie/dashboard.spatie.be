<?php

namespace App\Http\Controllers;

use App\Services\TweetHistory\TweetHistory;

class DashboardController
{
    public function __invoke()
    {
        return view('dashboard')->with([
            'pusherKey' => config('broadcasting.connections.pusher.key'),
            'initialTweets' => TweetHistory::all(),
        ]);
    }
}
