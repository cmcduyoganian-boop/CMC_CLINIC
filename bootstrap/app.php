<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustHosts(at: [
            '192.168.*',      // Any 192.168.x.x network
            '10.*',           // Any 10.x.x.x network
            '172.16.*',       // Any 172.16.x.x network
            'localhost',
            '127.0.0.1',
        ]);

        // Register custom middleware aliases for Laravel 11
        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin::class,
            'approval' => \App\Http\Middleware\CheckApprovalStatus::class,
            'clinic.role' => \App\Http\Middleware\ClinicStaff::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
