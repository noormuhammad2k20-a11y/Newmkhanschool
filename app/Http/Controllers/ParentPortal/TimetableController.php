<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Timetable;

class TimetableController extends Controller
{
    public function show($student_id)
    {
        $parent = auth()->user();
        
        $student = Student::where('id', $student_id)
            ->whereHas('user.linkedStudents', function($q) use ($parent) {
                $q->where('parent_user_id', $parent->id);
            })->firstOrFail();

        $timetables = Timetable::with(['subject', 'teacher'])
            ->where('class_id', $student->current_class_id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('parent.child-timetable', compact('student', 'timetables'));
    }
}
