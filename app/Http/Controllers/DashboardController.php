<?php

namespace App\Http\Controllers;

use App\Services\TweetHistory\TweetHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $pusherKey = config('broadcasting.connections.pusher.key');

        $initialTweets = (new TweetHistory())->getTweets();

        return view('dashboard')->with(compact('pusherKey', 'initialTweets'));
    }
}
