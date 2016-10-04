<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        /*
         * Authenticate the user's personal channel...
         */
        Broadcast::routes();

        Broadcast::channel('dashboard', function (User $user) {
            return true;
        });
    }
}
