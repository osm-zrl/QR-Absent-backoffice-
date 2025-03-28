<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsTeacher;
use App\Http\Middleware\IsStudent;
use App\Http\Middleware\ForceJsonRequestHeader;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        $middleware->append(ForceJsonRequestHeader::class);

        $middleware->appendToGroup('authTeacher', [
            IsTeacher::class
        ]);

        $middleware->appendToGroup('authStudent', [
            IsStudent::class
        ]);

        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {

            return response()->json([
                'message' => 'Not Authorized',
            ], 401);
        });
    })->create();
