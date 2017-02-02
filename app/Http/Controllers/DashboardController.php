<?php

namespace App\Http\Controllers;

use App\Services\TweetHistory\TweetHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $pusherKey = config('broadcasting.connections.pusher.key');
        $pusherCluster = config('broadcasting.connections.pusher.options.cluster');

        $initialTweets = (new TweetHistory())->getTweets();

        $usingNodeServer = usingNodeServer();

        return view('dashboard')->with(compact('pusherKey', 'pusherCluster', 'initialTweets', 'usingNodeServer'));
    }
}
