<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            Log::warning('CheckRole middleware: User not authenticated', [
                'path' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - User not authenticated'
            ], 401);
        }

        $user = auth()->user();

        // Check if user has one of the required roles
        if (!in_array($user->role, $roles)) {
            Log::warning('CheckRole middleware: Access denied', [
                'user_id' => $user->users_id ?? $user->id,
                'user_role' => $user->role,
                'required_roles' => $roles,
                'path' => $request->path()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Access denied. Insufficient permissions.',
                'debug' => [
                    'user_role' => $user->role,
                    'required_roles' => $roles
                ]
            ], 403);
        }

        Log::info('CheckRole middleware: Access granted', [
            'user_id' => $user->users_id ?? $user->id,
            'user_role' => $user->role,
            'required_roles' => $roles,
            'path' => $request->path()
        ]);

        return $next($request);
    }
}