<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Timetable;

class TimetableController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $timetables = Timetable::with(['teacher','subjectRef'])
            ->where('class_id', $student->current_class_id)
            ->where('section_id_ref', $student->current_section_id)
            ->orderByRaw("FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
            ->orderBy('start_time')
            ->get();

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

        return view('student.timetable', compact('timetables','days','student'));
    }
}
