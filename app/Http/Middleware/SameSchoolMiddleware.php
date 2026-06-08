<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class SameSchoolMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        // Super Admin bypasses school scope
        if (auth()->check() && auth()->user()->hasRole('Super Admin')) {
            return $next($request);
        }

        // For all other roles, every model access must be scoped to their school_id
        // This middleware stores the school_id in the request for controllers to use
        if (auth()->check()) {
            $request->merge(['_school_id' => auth()->user()->school_id]);
        }

        return $next($request);
    }
}
