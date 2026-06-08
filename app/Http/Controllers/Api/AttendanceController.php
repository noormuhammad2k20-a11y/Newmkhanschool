<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $classGrade = $request->query('class_grade');
            $sectionName = $request->query('section');
            $date = $request->query('date', date('Y-m-d'));

            $query = DB::table('students as s')
                ->join('classes as c', 's.current_class_id', '=', 'c.id')
                ->join('sections as sec', 's.current_section_id', '=', 'sec.id')
                ->leftJoin('student_attendances as a', function ($join) use ($date) {
                    $join->on('s.id', '=', 'a.student_id')
                         ->where('a.date', '=', $date);
                })
                ->select(
                    's.id', 
                    's.admission_no as roll_no', 
                    's.first_name', 
                    's.last_name',
                    'a.status as attendance_status'
                );

            if (auth()->check() && auth()->user()->role_id == 4) {
                // Student can only see their own attendance
                $student = DB::table('students')->where('user_id', auth()->id())->first();
                if ($student) {
                    $query->where('s.id', $student->id);
                } else {
                    $query->where('s.id', -1); // Force empty if no student record
                }
            }

            if ($classGrade) {
                $query->where('c.name', $classGrade);
            }

            if ($sectionName) {
                $query->where('sec.name', $sectionName);
            }

            $students = $query->orderBy('s.first_name')
                ->orderBy('s.last_name')
                ->limit(50)
                ->get();

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
        try {
            if (auth()->check() && auth()->user()->role_id == 4) {
                return response()->json(['status' => 'error', 'message' => 'Students are not allowed to mark attendance'], 403);
            }

            $action = $request->input('action');
            if ($action === 'save') {
                $attendanceData = $request->input('attendance', []);
                $date = $request->input('date', date('Y-m-d'));

                $activeYear = DB::table('academic_years')->where('is_active', 1)->first();
                $academicYearId = $activeYear ? $activeYear->id : 1;

                foreach ($attendanceData as $record) {
                    DB::table('student_attendances')->updateOrInsert(
                        [
                            'student_id' => $record['student_id'],
                            'date' => $date
                        ],
                        [
                            'academic_year_id' => $academicYearId,
                            'status' => $record['status'],
                            'marked_by' => auth()->id() // Will be null if not logged in, or the actual user ID
                        ]
                    );
                }

                return response()->json(['status' => 'success', 'message' => 'Attendance saved successfully']);
            }
            return response()->json(['status' => 'error', 'message' => 'Invalid action'], 400);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
