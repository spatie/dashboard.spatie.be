<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Authenticate the user's personal channel...
         */
        Broadcast::routes();

        Broadcast::channel('dashboard', function () {
            if (request()->getUser() != config('very_basic_auth.user')) {
                return false;
            }

            if (request()->getPassword() != config('very_basic_auth.password')) {
                return false;
            }

            return true;
        });
    }
}
