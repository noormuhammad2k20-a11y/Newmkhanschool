<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;

class AttendanceController extends Controller
{
    public function show($student_id)
    {
        $parent = auth()->user();
        
        // Ensure student belongs to parent
        $student = Student::where('id', $student_id)
            ->whereHas('user.linkedStudents', function($q) use ($parent) {
                $q->where('parent_user_id', $parent->id);
            })->firstOrFail();

        $attendances = StudentAttendance::where('student_id', $student->id)
            ->orderByDesc('date')
            ->paginate(30);

        return view('parent.child-attendance', compact('student', 'attendances'));
    }
}
