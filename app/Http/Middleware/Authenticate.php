<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::guard()->guest()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
    
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Kirim flag session_expired untuk ditangani oleh LoginController
            return route('login', ['session_expired' => true]);
        }
    }
}
