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

        // ✅ API ミドルウェアを有効化（これが抜けてた）
        $middleware->api([
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // LINE Webhook は CSRF 除外
        $middleware->validateCsrfTokens(except: [
            'api/line/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
