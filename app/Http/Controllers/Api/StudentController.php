<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = DB::table('students as s')
                ->leftJoin('classes as c', 's.current_class_id', '=', 'c.id')
                ->leftJoin('sections as sec', 's.current_section_id', '=', 'sec.id')
                ->select('s.*', 'c.name as class_name', 'sec.name as section_name');

            if (auth()->check() && auth()->user()->role_id == 3) {
                $teacher = DB::table('teachers')->where('user_id', auth()->id())->first();
                if ($teacher) {
                    $classIds = DB::table('timetables')->where('teacher', 'like', '%' . $teacher->full_name . '%')->pluck('class_id');
                    $query->whereIn('s.current_class_id', $classIds);
                } else {
                    $query->where('s.id', '<', 0); // Force empty result if no teacher profile
                }
            }

            if ($request->filled('search')) {
                $search = '%' . $request->search . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('s.first_name', 'LIKE', $search)
                      ->orWhere('s.last_name', 'LIKE', $search)
                      ->orWhere('s.admission_no', 'LIKE', $search)
                      ->orWhere('s.b_form_number', 'LIKE', $search);
                });
            }

            if ($request->filled('class_id')) {
                $query->where('s.current_class_id', $request->class_id);
            }

            if ($request->filled('section_id')) {
                $query->where('s.current_section_id', $request->section_id);
            }

            if ($request->filled('is_tuition')) {
                $query->where('s.is_tuition', $request->is_tuition);
            }

            if ($request->filled('status')) {
                $query->where('s.status', ucfirst($request->status));
            }

            $students = $query->orderBy('s.id', 'desc')->limit(50)->get();

            return response()->json([
                'status' => 'success',
                'data' => $students
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        if (!$request->filled(['first_name', 'admission_number', 'email'])) {
            return response()->json(['status' => 'error', 'message' => 'Missing required fields (First Name, Admission Number, Email).'], 400);
        }

        // Check if email already exists
        $existingUser = DB::table('users')->where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json(['status' => 'error', 'message' => 'The email address is already in use.'], 400);
        }

        try {
            DB::beginTransaction();

            $schoolId = auth()->check() ? auth()->user()->school_id : null;

            $userId = DB::table('users')->insertGetId([
                'name' => trim($request->first_name . ' ' . ($request->last_name ?? '')),
                'email' => $request->email,
                'password_hash' => bcrypt('password'),
                'role_id' => 4, // Student Role
                'school_id' => $schoolId,
                'status' => 'active',
                'created_at' => now()
            ]);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/students'), $filename);
                $photoPath = 'uploads/students/' . $filename;
            }

            DB::table('students')->insert([
                'user_id' => $userId,
                'admission_no' => $request->admission_number,
                'exam_roll' => $request->exam_roll ?? null,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name ?? '',
                'gender' => $request->gender ?? 'Other',
                'dob' => $request->date_of_birth ?? null,
                'b_form_number' => $request->national_id ?? null,
                'father_name' => $request->guardian_name ?? null,
                'father_cnic' => $request->guardian_id ?? null,
                'mobile_number' => $request->emergency_contact ?? null,
                'class_admitted' => $request->class_admitted ?? null,
                'current_class_id' => $request->current_class_id ?: null,
                'current_section_id' => $request->current_section_id ?: null,
                'admission_date' => $request->admission_date ?? null,
                'previous_school' => $request->previous_school ?? null,
                'current_school' => $request->current_school ?? null,
                'placeofbirth' => $request->placeofbirth ?? null,
                'address' => $request->address ?? null,
                'religion' => $request->religion ?? null,
                'caste' => $request->caste ?? null,
                'status' => $request->status ?? 'Regular',
                'is_tuition' => $request->is_tuition ?? 0,
                'photo' => $photoPath
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Student added successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (!$request->filled(['first_name', 'admission_number', 'email'])) {
            return response()->json(['status' => 'error', 'message' => 'Missing required fields (First Name, Admission Number, Email).'], 400);
        }

        try {
            DB::beginTransaction();

            $student = DB::table('students')->where('id', $id)->first();

            if ($student && $student->user_id) {
                $existingUser = DB::table('users')
                                  ->where('email', $request->email)
                                  ->where('id', '!=', $student->user_id)
                                  ->first();
                if ($existingUser) {
                    return response()->json(['status' => 'error', 'message' => 'The email address is already in use by another user.'], 400);
                }

                DB::table('users')->where('id', $student->user_id)->update([
                    'name' => trim($request->first_name . ' ' . ($request->last_name ?? '')),
                    'email' => $request->email
                ]);
            }

            $updateData = [
                'admission_no' => $request->admission_number,
                'exam_roll' => $request->exam_roll ?? null,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name ?? '',
                'gender' => $request->gender ?? 'Other',
                'dob' => $request->date_of_birth ?? null,
                'b_form_number' => $request->national_id ?? null,
                'father_name' => $request->guardian_name ?? null,
                'father_cnic' => $request->guardian_id ?? null,
                'mobile_number' => $request->emergency_contact ?? null,
                'class_admitted' => $request->class_admitted ?? null,
                'current_class_id' => $request->current_class_id ?: null,
                'current_section_id' => $request->current_section_id ?: null,
                'admission_date' => $request->admission_date ?? null,
                'previous_school' => $request->previous_school ?? null,
                'current_school' => $request->current_school ?? null,
                'placeofbirth' => $request->placeofbirth ?? null,
                'address' => $request->address ?? null,
                'religion' => $request->religion ?? null,
                'caste' => $request->caste ?? null,
                'status' => $request->status ?? 'Regular',
                'is_tuition' => $request->is_tuition ?? 0
            ];

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/students'), $filename);
                $updateData['photo'] = 'uploads/students/' . $filename;
            }

            DB::table('students')->where('id', $id)->update($updateData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Student updated successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $student = Student::find($id);
            if (!$student) {
                return response()->json(['status' => 'error', 'message' => 'Student not found.'], 404);
            }
            $student->delete(); // Soft delete

            return response()->json([
                'status' => 'success',
                'message' => 'Student removed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            $student = Student::withTrashed()->find($id);
            if (!$student) {
                return response()->json(['status' => 'error', 'message' => 'Student not found.'], 404);
            }
            $student->restore();

            return response()->json([
                'status' => 'success',
                'message' => 'Student restored successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
