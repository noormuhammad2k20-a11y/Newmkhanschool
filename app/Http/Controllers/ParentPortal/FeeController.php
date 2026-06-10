<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Fee;

class FeeController extends BaseParentController
{
    public function show($student_id)
    {
        \App\Models\ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
            
        $student = Student::findOrFail($student_id);

        $fees = Fee::where('student_id', $student->id)
            ->orderByDesc('due_date')
            ->get();

        return view('parent.child-fees', compact('student', 'fees'));
    }
}
