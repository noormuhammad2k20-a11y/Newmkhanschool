<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = \App\Models\ExamSchedule::with(['class_', 'subjectRelation'])->orderBy('exam_date', 'asc')->get();
        $classes = \App\Models\SchoolClass::all();
        $subjects = \App\Models\Subject::all();
        return view('exams.index', compact('exams', 'classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_type' => 'required',
            'class_id' => 'required',
            'subject_id' => 'required',
            'exam_date' => 'required|date',
            'exam_time' => 'required',
            'max_marks' => 'required|numeric',
            'passing_marks' => 'required|numeric',
        ]);

        $class = \App\Models\SchoolClass::find($request->class_id);
        $subject = \App\Models\Subject::find($request->subject_id);

        \App\Models\ExamSchedule::create([
            'exam_type' => $request->exam_type,
            'class_id' => $request->class_id,
            'class_name' => $class ? $class->name : null,
            'subject_id' => $request->subject_id,
            'subject' => $subject ? $subject->name : null,
            'exam_date' => $request->exam_date,
            'exam_time' => $request->exam_time,
            'max_marks' => $request->max_marks,
            'passing_marks' => $request->passing_marks,
            'status' => $request->status ?? 'Scheduled',
            'school_id' => 1,
            'academic_year_id' => 1,
        ]);

        return redirect()->back()->with('success', 'Exam scheduled successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'exam_type' => 'required',
            'class_id' => 'required',
            'subject_id' => 'required',
            'exam_date' => 'required|date',
            'exam_time' => 'required',
            'max_marks' => 'required|numeric',
            'passing_marks' => 'required|numeric',
            'status' => 'required',
        ]);

        $exam = \App\Models\ExamSchedule::findOrFail($id);
        $class = \App\Models\SchoolClass::find($request->class_id);
        $subject = \App\Models\Subject::find($request->subject_id);

        $exam->update([
            'exam_type' => $request->exam_type,
            'class_id' => $request->class_id,
            'class_name' => $class ? $class->name : null,
            'subject_id' => $request->subject_id,
            'subject' => $subject ? $subject->name : null,
            'exam_date' => $request->exam_date,
            'exam_time' => $request->exam_time,
            'max_marks' => $request->max_marks,
            'passing_marks' => $request->passing_marks,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Exam schedule updated successfully.');
    }

    public function destroy($id)
    {
        \App\Models\ExamSchedule::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Exam deleted successfully.');
    }

    public function marks()
    {
        return view('exams.marks');
    }
}
