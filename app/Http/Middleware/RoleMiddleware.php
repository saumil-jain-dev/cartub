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
        $user = auth()->user();
        if (!$user) {
            return fail([], 'Unauthorized', config('code.UNAUTHORIZED_CODE'));
            
        }

        if ($user->role !== $role) {
            return fail([], 'Access denied for your role.', config('code.PERMISSION_CODE'));
            
        }
        return $next($request);
    }
}
