<?php

namespace App\Services\Officient;

use Illuminate\Support\ServiceProvider;

class OfficientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Officient::class, fn () => Officient::create((string) config('services.officient.token')));
    }
}
