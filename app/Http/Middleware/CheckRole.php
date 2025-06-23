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

            // Return appropriate response based on request type
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - User not authenticated'
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // If no specific roles required, allow any authenticated user
        if (empty($roles)) {
            return $next($request);
        }

        // If user is admin, allow access to everything
        if ($user->role === 'AD') {
            return $next($request);
        }

        // For non-admin users, check if they have one of the required roles
        if (!in_array($user->role, $roles)) {
            Log::warning('CheckRole middleware: Access denied', [
                'user_id' => $user->users_id ?? $user->id,
                'user_role' => $user->role,
                'required_roles' => $roles,
                'path' => $request->path()
            ]);

            // Return appropriate response based on request type
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Insufficient permissions.',
                    'debug' => [
                        'user_role' => $user->role,
                        'required_roles' => $roles
                    ]
                ], 403);
            }

            return redirect()->route('login')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
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
