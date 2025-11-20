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
        // ESTA ES LA PARTE A AÑADIR
        $middleware->redirectTo(
            guests: '/login', // A donde van los no autenticados
            users: '/dashboard' // A donde van los ya autenticados si intentaban ir a 'login', 'register', etc
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
