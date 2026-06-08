<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function show($student_id)
    {
        $parent = auth()->user();
        
        $student = Student::where('id', $student_id)
            ->whereHas('user.linkedStudents', function($q) use ($parent) {
                $q->where('parent_user_id', $parent->id);
            })->firstOrFail();

        $assignments = Assignment::with(['subject', 'teacher'])
            ->where('class_id', $student->current_class_id)
            ->orderByDesc('due_date')
            ->get();

        return view('parent.child-assignments', compact('student', 'assignments'));
    }
}
