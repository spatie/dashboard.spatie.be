<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $pusherCluster = config('broadcasting.connections.pusher.options.custer');
        $pusherEncrypted = config('broadcasting.connections.pusher.options.encrypted');
        $pusherKey = config('broadcasting.connections.pusher.key');

        return view('dashboard')->with(compact('pusherCluster', 'pusherEncrypted', 'pusherKey'));
    }
}
