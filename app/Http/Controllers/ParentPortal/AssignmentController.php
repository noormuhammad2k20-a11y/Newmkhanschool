<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function show($student_id)
    {
        \App\Models\ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
            
        $student = Student::with('currentClass')->findOrFail($student_id);

        $assignments = Assignment::with(['subject', 'teacher'])
            ->where('class_id', $student->current_class_id)
            ->orderByDesc('due_date')
            ->get();

        return view('parent.child-assignments', compact('student', 'assignments'));
    }
}
