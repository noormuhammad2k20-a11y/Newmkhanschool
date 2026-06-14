<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Bypass global scopes to ensure we can always find the user by email during login
        $user = \App\Models\User::withoutGlobalScopes()->where('email', $credentials['email'])->first();
        
        $hashCheck = false;
        if ($user) {
            $hashCheck = \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->getAuthPassword());
        }
        
        \Log::info('Login Attempt:', [
            'email' => $credentials['email'],
            'user_found' => $user ? true : false,
        ]);

        if ($user) {
            // Determine the guard based on role_id
            // 1=Super Admin, 2=School Admin, 3=Teacher, 4=Student, 5=Parent, 6=Accountant
            $guard = 'web';
            if (in_array($user->role_id, [1, 2])) {
                $guard = 'admin';
            } elseif ($user->role_id == 3) {
                $guard = 'teacher';
            } elseif ($user->role_id == 4) {
                $guard = 'student';
            } elseif ($user->role_id == 5) {
                $guard = 'parent';
            } elseif ($user->role_id == 6) {
                $guard = 'accountant';
            }

            $remember = $request->filled('remember');

            if ($hashCheck) {
                // Log the user in manually, bypassing EloquentUserProvider global scope issues
                Auth::guard($guard)->login($user, $remember);
                // Only regenerate the session, do not invalidate it to preserve other sessions!
                $request->session()->regenerate();
                
                if (in_array($user->role_id, [1, 2])) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role_id == 3) {
                    return redirect()->route('teacher.dashboard');
                } elseif ($user->role_id == 4) {
                    return redirect()->route('student.dashboard');
                } elseif ($user->role_id == 5) {
                    return redirect()->route('parent.dashboard');
                } elseif ($user->role_id == 6) {
                    return redirect()->route('accountant.dashboard');
                }

                return redirect('/');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $guard = $request->input('guard', 'web');
        
        Auth::guard($guard)->logout();
        
        // Do NOT invalidate the entire session here because we might be logged into other guards!
        // Instead, just redirect to login page.
        return redirect('/login');
    }
}
