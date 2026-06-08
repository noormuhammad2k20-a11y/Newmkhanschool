<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Mark;

class MarksController extends Controller
{
    public function show($student_id)
    {
        $parent = auth()->user();
        
        $student = Student::where('id', $student_id)
            ->whereHas('user.linkedStudents', function($q) use ($parent) {
                $q->where('parent_user_id', $parent->id);
            })->firstOrFail();

        $marks = Mark::with(['subject', 'examType', 'academicYear'])
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        return view('parent.child-marks', compact('student', 'marks'));
    }
}
