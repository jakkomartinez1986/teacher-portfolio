<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
       then: function () {
            Route::middleware('web','auth')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
            Route::middleware('web','auth')
                ->prefix('settings')
                ->name('settings.')
                ->group(base_path('routes/settings.php'));
            Route::middleware('web','auth')
                ->prefix('documents')
                ->name('documents.')
                ->group(base_path('routes/documents.php'));
            Route::middleware('web','auth')
                ->prefix('academic')
                ->name('academic.')
                ->group(base_path('routes/academic.php'));
            Route::middleware('web','auth')
                ->prefix('students')
                ->name('students.')
                ->group(base_path('routes/students.php'));
         
            
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
