<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
            ->middleware([
                'middleware' => 'api',
            ])
            ->group(function ($router) {
                require base_path('routes/api.php');
            });
    }
}
