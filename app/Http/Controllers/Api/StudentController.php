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
        if (!$request->filled(['first_name', 'admission_number'])) {
            return response()->json(['status' => 'error', 'message' => 'Missing required fields.'], 400);
        }

        try {
            DB::table('students')->insert([
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
                'current_section_id' => $request->section ?: null,
                'admission_date' => $request->admission_date ?? null,
                'previous_school' => $request->previous_school ?? null,
                'current_school' => $request->current_school ?? null,
                'placeofbirth' => $request->placeofbirth ?? null,
                'address' => $request->address ?? null,
                'religion' => $request->religion ?? null,
                'caste' => $request->caste ?? null,
                'status' => $request->status ?? 'Regular',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student added successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (!$request->filled(['first_name', 'admission_number'])) {
            return response()->json(['status' => 'error', 'message' => 'Missing required fields.'], 400);
        }

        try {
            DB::table('students')->where('id', $id)->update([
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
                'current_section_id' => $request->section_id ?: null,
                'admission_date' => $request->admission_date ?? null,
                'previous_school' => $request->previous_school ?? null,
                'current_school' => $request->current_school ?? null,
                'placeofbirth' => $request->placeofbirth ?? null,
                'address' => $request->address ?? null,
                'religion' => $request->religion ?? null,
                'caste' => $request->caste ?? null,
                'status' => $request->status ?? 'Regular',
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Student updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Delete related records first to avoid foreign key constraint violations
            DB::table('marks')->where('student_id', $id)->delete();
            DB::table('attendance_records')->where('student_id', $id)->delete();
            // DB::table('fee_records')->where('student_id', $id)->delete(); // If fee_records exist

            DB::table('students')->where('id', $id)->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Student removed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
