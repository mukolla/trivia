<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Facades\JWTAuth;

//use Tymon\JWTAuth\JWTAuth;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            if ($e instanceof UserNotDefinedException) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } elseif ($e instanceof TokenExpiredException) {
                return response()->json(['error' => 'Unauthorized: Token has expired.'], 401);
            } elseif ($e instanceof TokenInvalidException) {
                return response()->json(['error' => 'Unauthorized: Invalid authorization token.'], 401);
            } elseif ($e instanceof JWTException) {
                return response()->json(['error' => 'Unauthorized: Missing authorization token.'], 401);
            } else {
                return response()->json(['error' => 'Unauthorized. Error.'], 401);
            }
        }

        if (! $user) {
            return response()->json(['error' => 'Unauthorized: User not found.'], 401);
        }

        return $next($request);
    }
}
