<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware aliases are now defined in app/Http/Kernel.php
        $aliases = \App\Http\Kernel::getAliases();
        $middleware->alias($aliases);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
