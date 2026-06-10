<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;
use App\Models\Student;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function show($student_id)
    {
        $check = ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
        
        $student = Student::with('currentClass')->findOrFail($student_id);
        $schedules = ExamSchedule::where('class_id', $student->current_class_id)
            ->with('subjectRelation')
            ->orderBy('exam_date')
            ->get();
            
        return view('parent.child-exam-schedule', compact('student', 'schedules'));
    }
}
