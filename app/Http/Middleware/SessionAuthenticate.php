<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SessionAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
