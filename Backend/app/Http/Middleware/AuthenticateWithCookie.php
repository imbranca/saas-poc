<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithCookie
{
    public function handle(Request $request, Closure $next)
    {
      $token = $request->cookie('jwt');
      // if(!$token){
      //     return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
      // }

      if ($token) {
        $request->headers->set('Authorization', 'Bearer ' . $token);
      }
      return $next($request);
    }
}