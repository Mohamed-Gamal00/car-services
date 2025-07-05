<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenVerify
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next) // Middleware
    {
        $token = $request->header('API-TOKEN');
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $authToken = UserToken::where('token', $token)->first();

        if (!$authToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $request->setUserResolver(function () use ($authToken) {
            return $authToken->user;
        });

        return $next($request);
    }

}
