<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        // in order to user private channels a user needs to be logged in
        // if ($user = User::first()) {
        //     auth()->login($user);
        // }

        $this->registerPolicies();
    }
}
