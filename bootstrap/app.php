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
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role'          => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission'    => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'school.active'  => \App\Http\Middleware\EnsureSchoolIsActive::class,
        'admin.auth'     => \App\Http\Middleware\EnsureAdminIsAuthenticated::class,
        'admin.can'      => \App\Http\Middleware\EnsureAdminCan::class,
        'school.member'  => \App\Http\Middleware\EnsureSchoolMember::class,
        'school.module'  => \App\Http\Middleware\EnsureSchoolHasModule::class,
    ]);

    // Páginas HTML nunca são cacheadas (navegador/LiteSpeed) — assets com hash mantêm cache.
    $middleware->web(append: [
        \App\Http\Middleware\NoHtmlCache::class,
    ]);
})


    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
