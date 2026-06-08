<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamSchedule;
use App\Models\AcademicYear;

class ExamController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $academicYear = AcademicYear::where('is_active', 1)->first();

        $schedules = ExamSchedule::with(['subjectRelation'])
            ->where('class_id', $student->current_class_id)
            ->where('academic_year_id', $academicYear?->id)
            ->orderBy('exam_date')
            ->get();

        return view('student.exam-schedule', compact('schedules'));
    }
}
