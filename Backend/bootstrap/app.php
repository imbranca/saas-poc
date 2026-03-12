<?php

use App\Http\Middleware\AuthenticateWithCookie;
use App\Http\Middleware\Cors;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(AuthenticateWithCookie::class);

        })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
      $exceptions->render(function (AuthorizationException $e) {
        return response()->json(['message' => 'Unauthorized'],
            Response::HTTP_FORBIDDEN);
      });

      $exceptions->render(function (ModelNotFoundException $e) {
            return response()->json(['message' => 'Resource not found'],
            Response::HTTP_NOT_FOUND);
      });

      $exceptions->render(function (ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors'  => $e->errors(),
        ], Response::HTTP_BAD_REQUEST);
    });

      $exceptions->render(function (Exception $e) {
        return response()->json([
          'message' => 'An unexpected error occurred',
          'errors' =>  config('app.debug') ? $e->getMessage() : null,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
      });

  })->create();
