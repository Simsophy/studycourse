<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and is an admin using admins guard
        if (!auth('admins')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Admin access required',
            ], 403);
        }

        return $next($request);
    }
}
