<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Timetable;

class TimetableController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $timetable = Timetable::with(['teacher','subjectRef'])
            ->where('class_id', $student->current_class_id)
            ->where('section_id_ref', $student->current_section_id)
            ->orderByRaw("FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

        return view('student.timetable', compact('timetable','days','student'));
    }
}
