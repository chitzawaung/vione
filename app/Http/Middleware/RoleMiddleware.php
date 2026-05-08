<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            // If user is admin and trying to access user routes, redirect to admin dashboard
            if ($request->user() && $request->user()->role === 'admin') {
                return redirect()->route('dashboard');
            }
            
            // If user doesn't have required role, redirect to products (for users) or dashboard (for guests)
            if ($request->user()) {
                return redirect()->route('products.index');
            }
            
            return redirect()->route('login');
        }

        return $next($request);
    }
}
