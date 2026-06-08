<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;

class CheckTeacherModule
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $module): Response
    {
        if (auth()->check() && auth()->user()->role_id == 3) {
            $teacher = Teacher::where('user_id', auth()->id())->first();
            if ($teacher) {
                // Dashboard is always accessible
                if ($module === 'dashboard') {
                    return $next($request);
                }

                $hasAccess = DB::table('teacher_module_access')
                    ->where('teacher_id', $teacher->id)
                    ->where('module_name', $module)
                    ->exists();
                
                if (!$hasAccess) {
                    return redirect()->route('teacher.dashboard')->with('error', 'You do not have permission to access the ' . ucfirst($module) . ' module.');
                }
            }
        }

        return $next($request);
    }
}
