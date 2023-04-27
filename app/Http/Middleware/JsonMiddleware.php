<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isJson() &&
            $request->header('Content-Type') !== 'application/json' &&
            ! empty($request->getContent())
        ) {
            return response()->json(['error' => 'Only JSON requests are allowed.'], 400);
        }

        $request->headers->set('Accept', 'application/json');
        $request->setJson(null);

        return $next($request);
    }
}
