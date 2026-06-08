<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index()
    {
        $exams = [
            ['type' => 'Annual Examination', 'class' => 'Class XII (Sci)', 'subject' => 'Physics - Theory', 'date' => '15 Mar 2024', 'time' => '09:00 AM - 12:00 PM', 'status' => 'In Progress'],
            ['type' => 'Annual Examination', 'class' => 'Class XII (Arts)', 'subject' => 'History', 'date' => '15 Mar 2024', 'time' => '09:00 AM - 12:00 PM', 'status' => 'Scheduled'],
            ['type' => 'Midterm Assessment', 'class' => 'Class X', 'subject' => 'Mathematics', 'date' => '12 Mar 2024', 'time' => '10:00 AM - 01:00 PM', 'status' => 'Completed'],
            ['type' => 'Annual Examination', 'class' => 'Class XII (Com)', 'subject' => 'Accountancy', 'date' => '18 Mar 2024', 'time' => '09:00 AM - 12:00 PM', 'status' => 'Scheduled'],
            ['type' => 'Unit Test II', 'class' => 'Class VIII', 'subject' => 'General Science', 'date' => '20 Mar 2024', 'time' => '11:00 AM - 12:30 PM', 'status' => 'Scheduled']
        ];

        return response()->json([
            'status' => 'success',
            'data' => $exams
        ]);
    }

    public function getMarks()
    {
        try {
            $students = DB::table('students')
                ->join('classes', 'students.current_class_id', '=', 'classes.id')
                ->where('classes.name', 'Class X')
                ->select('students.id', 'students.admission_number as roll_no', 'students.first_name', 'students.last_name')
                ->orderBy('students.first_name')
                ->orderBy('students.last_name')
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

    public function storeMarks(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Marks saved successfully']);
    }
}
