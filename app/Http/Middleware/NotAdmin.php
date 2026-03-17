<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NotAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_admin) {
            abort(403, 'Admins cannot access student routes');
        }
        return $next($request);
    }
}