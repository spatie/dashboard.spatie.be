<?php

use App\Http\Middleware\AccessToken;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
    )
    ->withCommands(
        glob(__DIR__ . '/../app/Tiles/*', GLOB_ONLYDIR),
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
        ]);

        $middleware->alias([
            'access-token' => AccessToken::class,
        ]);

        $middleware->api(prepend: [
            AccessToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (UnauthorizedHttpException $e) {
            return new Response('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
        });
    })
    ->booted(function () {
        Gate::define('viewWebSocketsDashboard', function ($user = null) {
            if (app()->environment('local')) {
                return true;
            }

            if (in_array($_SERVER['HTTP_X_FORWARDED_FOR'] ?? '', config('websockets.allowed_dashboard_ips'))) {
                return true;
            }

            return false;
        });

        try {
            if (Schema::hasTable(with(new User)->getTable())) {
                if ($user = User::first()) {
                    auth()->login($user);
                }
            }
        } catch (\Throwable) {
            // Database not available
        }
    })
    ->create();
