<?php

namespace App\Http\Controllers;

use App\Services\TweetHistory\TweetHistory;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard')->with([
            'pusherKey' => config('broadcasting.connections.pusher.key'),

            'pusherCluster' => config('broadcasting.connections.pusher.options.cluster'),

            'initialTweets' => TweetHistory::all(),

            'usingNodeServer' => usingNodeServer(),
        ]);
    }
}
