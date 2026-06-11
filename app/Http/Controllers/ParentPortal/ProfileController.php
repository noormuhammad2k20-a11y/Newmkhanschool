<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\AjaxResponseTrait;

class ProfileController extends BaseParentController
{
    use AjaxResponseTrait;
    public function show()
    {
        $user = auth()->user();
        return view('parent.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        
        if ($request->password) {
            $user->password_hash = Hash::make($request->password);
        }

        $user->save();

        return $this->ajaxSuccess($request, 'Profile updated successfully.');
    }
}
