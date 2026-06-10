<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Timetable;

class TimetableController extends Controller
{
    public function show($student_id)
    {
        \App\Models\ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
            
        $student = Student::with('currentClass', 'currentSection')->findOrFail($student_id);

        $timetables = Timetable::with(['subject', 'teacher'])
            ->where('class_id', $student->current_class_id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('parent.child-timetable', compact('student', 'timetables'));
    }
}
