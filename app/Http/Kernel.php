<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to assign middleware to routes and groups.
     *
     * @var array<string, 
     */
    protected $middlewareAliases = [
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ];

    /**
     * Get the middleware aliases for bootstrap registration.
     *
     * @return array<string, 
     */
    public static function getAliases(): array
    {
        return [
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ];
    }
}

