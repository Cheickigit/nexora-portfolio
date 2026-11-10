<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/portfolio-admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SetOwnerPortfolio::class,
        ]);

        $middleware->alias([
            'portfolio' => \App\Http\Middleware\ResolvePortfolio::class,
            'license'   => \App\Http\Middleware\EnsureLicenseIsValid::class, // <-- LIGNE AJOUTÉE
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();