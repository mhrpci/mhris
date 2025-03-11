<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole('Super Admin')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Only Super Admin can access this resource.'], 403);
            }
            abort(403, 'Unauthorized. Only Super Admin can access this resource.');
        }

        return $next($request);
    }
} 