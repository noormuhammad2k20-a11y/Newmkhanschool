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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        
        // 1=Super Admin, 2=School Admin, 3=Teacher, 4=Student, 5=Parent
        $roleMap = [
            'admin' => [1, 2],
            'teacher' => [3],
            'student' => [4],
            'parent' => [5],
        ];

        if (!isset($roleMap[$role]) || !in_array($user->role_id, $roleMap[$role])) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
