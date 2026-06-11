<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use AjaxResponseTrait;
    public function show()
    {
        $student = auth()->user()->student;
        $user = auth()->user();
        return view('student.profile', compact('student', 'user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $student = $user->student;

        $request->validate([
            'mobile_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('student_photos', 'public');
            $student->photo = $path;
        }

        $student->mobile_number = $request->mobile_number;
        $student->address = $request->address;
        $student->save();

        if ($request->password) {
            $user->password_hash = Hash::make($request->password);
            $user->save();
        }

        return $this->ajaxSuccess($request, 'Profile updated successfully.');
    }
}
