<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // First check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in first.');
        }

        // Then check if user is an admin
        if (!Auth::user()->isAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}
