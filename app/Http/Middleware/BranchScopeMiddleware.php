<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchScopeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // If user is super admin, they can switch branches via session
            if ($user->role_id === 1 || $user->role->name === 'Super Admin') {
                $activeBranchId = session('active_branch_id', $user->school_id);
            } else {
                // Otherwise lock to their assigned school_id
                $activeBranchId = $user->school_id;
            }

            // Share the active branch ID globally so models can use it for scoping
            app()->instance('active_branch_id', $activeBranchId);
        }

        return $next($request);
    }
}
