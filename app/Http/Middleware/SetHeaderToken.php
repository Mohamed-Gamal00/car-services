<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetHeaderToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next) // Middleware
    {
        $token = $request->header('API-TOKEN');
//        return $request->headers;
        $request->headers->set('Authorization', " Bearer $token");

        return $next($request);
    }
}
