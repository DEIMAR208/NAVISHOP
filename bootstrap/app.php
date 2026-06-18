<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route; // <-- IMPORTANTE: No olvides añadir esta línea arriba

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // 1. Rutas de Usuario (El control de acceso se maneja dentro del archivo)
            Route::middleware(['web'])
                ->group(base_path('routes/usuario/usuarioweb.php'));

            // 2. Rutas de Creador (Requieren estar logueado y ser creador)
            Route::middleware(['web', 'auth', 'role:creador'])
                ->prefix('creador')
                ->group(base_path('routes/creador/creador.php'));

            // 3. Rutas de Admin (Requieren estar logueado y ser admin)
            Route::middleware(['web', 'auth', 'role:admin'])
                ->prefix('admin')
                ->group(base_path('routes/admin/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Mantenemos esto intacto para que Vue e Inertia sigan funcionando perfectamente
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Aquí registramos el alias del guardia 'role' que creamos antes
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
