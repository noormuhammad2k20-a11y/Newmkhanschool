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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Redirect based on role ID
            // 1=Super Admin, 2=School Admin, 3=Teacher, 4=Student, 5=Parent
            if (in_array($user->role_id, [1, 2])) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role_id == 3) {
                return redirect()->route('teacher.dashboard');
            } elseif ($user->role_id == 4) {
                return redirect()->route('student.dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
