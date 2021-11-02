<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->group([
                'middleware' => 'api',
            ], function ($router) {
                require base_path('routes/api.php');
            });
    }
}
