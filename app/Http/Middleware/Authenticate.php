<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!auth()->check()) {
            return $this->redirectTo($request);
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to if unauthenticated.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            // Admin routes
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->route('admin.login');
            }

            // Student routes
            if ($request->is('user') || $request->is('user/*')) {
                return redirect()->route('user.login');
            }

            // fallback
            return redirect()->route('user.login');
        }
    }
}