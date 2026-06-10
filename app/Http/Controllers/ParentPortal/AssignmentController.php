<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assignment;

class AssignmentController extends BaseParentController
{
    public function show($student_id)
    {
        \App\Models\ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
            
        $student = Student::with('currentClass')->findOrFail($student_id);

        $assignments = Assignment::with(['subject', 'teacher'])
            ->where('class_id', $student->current_class_id)
            ->orderByDesc('due_date')
            ->get()
            ->map(function ($a) use ($student_id) {
                $a->submission = \App\Models\AssignmentSubmission::where('assignment_id', $a->id)
                    ->where('student_id', $student_id)->first();
                return $a;
            });

        return view('parent.child-assignments', compact('student', 'assignments'));
    }
}
