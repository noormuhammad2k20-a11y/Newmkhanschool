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
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
            'same_school' => \App\Http\Middleware\SameSchoolMiddleware::class,
            'teacher_module' => \App\Http\Middleware\CheckTeacherModule::class,
        ]);
        
        $middleware->web(append: [
            \App\Http\Middleware\BranchScopeMiddleware::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            'login', '/login'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'CSRF token mismatch.'], 419);
            }
            return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
        });
    })->create();
