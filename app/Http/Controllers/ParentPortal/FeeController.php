<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Fee;

class FeeController extends Controller
{
    public function show($student_id)
    {
        $parent = auth()->user();
        
        $student = Student::where('id', $student_id)
            ->whereHas('user.linkedStudents', function($q) use ($parent) {
                $q->where('parent_user_id', $parent->id);
            })->firstOrFail();

        $fees = Fee::where('student_id', $student->id)
            ->orderByDesc('due_date')
            ->get();

        return view('parent.child-fees', compact('student', 'fees'));
    }
}
