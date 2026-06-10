<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;

class AttendanceController extends Controller
{
    public function show($student_id)
    {
        \App\Models\ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
            
        $student = Student::findOrFail($student_id);

        $attendances = StudentAttendance::where('student_id', $student->id)
            ->orderByDesc('date')
            ->paginate(30);

        return view('parent.child-attendance', compact('student', 'attendances'));
    }
}
