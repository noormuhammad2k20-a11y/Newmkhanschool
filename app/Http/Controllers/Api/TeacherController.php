<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\User;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Teacher::query();

            if ($request->filled('search')) {
                $search = '%' . $request->search . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', $search)
                      ->orWhere('employee_number', 'LIKE', $search)
                      ->orWhere('specialization', 'LIKE', $search);
                });
            }

            $teachers = $query->orderBy('full_name', 'asc')->get();

            return response()->json([
                'status' => 'success',
                'data' => $teachers
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        if (!$request->filled(['first_name', 'email', 'subject_specialization'])) {
            return response()->json(['status' => 'error', 'message' => 'Missing required fields.'], 400);
        }

        try {
            DB::beginTransaction();

            // Check if email already exists
            if ($request->filled('email') && User::where('email', $request->email)->exists()) {
                return response()->json(['status' => 'error', 'message' => 'Email address is already in use by another account.'], 400);
            }

            $fullName = trim($request->first_name . ' ' . ($request->last_name ?? ''));
            $email = $request->email ?? strtolower($request->first_name . rand(1000,9999) . '@school.com');

            // Create login user account
            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'password_hash' => bcrypt('password123'),
                'role_id' => 3, // Teacher role
                'status' => 'active',
            ]);

            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->employee_number = 'EMP-' . rand(1000, 9999);
            $teacher->full_name = $fullName;
            $teacher->email = $email;
            $teacher->cnic = $request->cnic ?? null;
            $teacher->mobile = $request->phone ?? null;
            $teacher->qualification = $request->qualification ?? null;
            $teacher->specialization = $request->subject_specialization ?? null;
            $teacher->experience = $request->filled('experience') ? (int) $request->experience : null;
            
            $teacher->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Teacher added successfully.',
                'id' => $teacher->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $teacher = Teacher::findOrFail($id);
            $user = null;
            
            if ($teacher->user_id) {
                $user = User::find($teacher->user_id);
            }

            if ($request->filled('first_name')) {
                $teacher->full_name = trim($request->first_name . ' ' . ($request->last_name ?? ''));
                if ($user) $user->name = $teacher->full_name;
            }
            if ($request->filled('email')) {
                // Check if email already exists for another user
                if ($user && $user->email !== $request->email && User::where('email', $request->email)->exists()) {
                    return response()->json(['status' => 'error', 'message' => 'Email address is already in use by another account.'], 400);
                }
                $teacher->email = $request->email;
                if ($user) $user->email = $teacher->email;
            }
            if ($request->filled('phone')) $teacher->mobile = $request->phone;
            if ($request->filled('cnic')) $teacher->cnic = $request->cnic;
            if ($request->filled('qualification')) $teacher->qualification = $request->qualification;
            if ($request->filled('subject_specialization')) $teacher->specialization = $request->subject_specialization;
            if ($request->filled('experience')) $teacher->experience = (int) $request->experience;
            
            $teacher->save();
            if ($user) $user->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Teacher updated successfully.',
                'data' => $teacher
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $teacher = Teacher::findOrFail($id);
            if ($teacher->user_id) {
                User::where('id', $teacher->user_id)->delete();
            }
            $teacher->delete();
            
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Teacher deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
