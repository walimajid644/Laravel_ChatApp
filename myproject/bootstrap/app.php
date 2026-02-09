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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'custom_auth' => \App\Http\Middleware\CustomTokenAuth::class, 
            'workspace.owner' => \App\Http\Middleware\WorkspaceMiddleware::class,
            'channel.access' => \App\Http\Middleware\ChannelMiddleware::class,
            'message.owner' => \App\Http\Middleware\MessageMiddleware::class,
            'team.access' => \App\Http\Middleware\TeamMiddleware::class,
            'team.member' => \App\Http\Middleware\TeamMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
