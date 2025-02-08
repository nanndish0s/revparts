<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request is an API request
        if ($request->is('api/*')) {
            // If the request is an API request and the user is not authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'errors' => 'You need to be authenticated to access this resource.'
                ], 401);
            }
        }

        return $next($request);
    }
}
