<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Mark;

class MarksController extends BaseParentController
{
    public function show($student_id)
    {
        \App\Models\ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
            
        $student = Student::findOrFail($student_id);

        $marks = Mark::with(['subject', 'examType', 'academicYear'])
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        return view('parent.child-marks', compact('student', 'marks'));
    }
}
