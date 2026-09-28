<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth'            => \App\Http\Middleware\Authenticate::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
            'curso.selecionado' => \App\Http\Middleware\CursoSelecionado::class,
            'somente.admin'   => \App\Http\Middleware\SomenteAdmin::class,
            'token'           => \App\Http\Middleware\TipoToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A API sempre responde erros em JSON (401, 403, 404, 422, 429...),
        // mesmo se o app não mandar "Accept: application/json".
        $exceptions->shouldRenderJsonWhen(
            fn ($request) => $request->is('api/*') || $request->expectsJson()
        );
    })->create();