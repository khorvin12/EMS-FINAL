<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HRMiddleware
{
    /**
     * Only allow HR users to access these routes.
     * Redirects to 403 Forbidden if not HR.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in AND has HR role
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            abort(403);
        }

        return $next($request);
    }
}