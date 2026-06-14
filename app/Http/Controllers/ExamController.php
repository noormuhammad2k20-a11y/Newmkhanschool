<?php

namespace App\Http\Controllers;

use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    use AjaxResponseTrait;
    public function index()
    {
        $allExams = \App\Models\ExamSchedule::with(['class_', 'subjectRelation'])->orderBy('exam_date', 'asc')->get();
        $groupedExams = $allExams->groupBy(function($exam) {
            return $exam->class_id . '-' . $exam->exam_type;
        });
        $classes = \App\Models\SchoolClass::all();
        $subjects = \App\Models\Subject::all();
        return view('exams.index', compact('groupedExams', 'classes', 'subjects'));
    }

    public function getSubjectsByClass($class_id)
    {
        $subjects = \App\Models\Subject::where('class_id', $class_id)->get();
        return response()->json($subjects);
    }

    public function getEventSchedules($class_id, $exam_type)
    {
        $schedules = \App\Models\ExamSchedule::select('exam_schedules.*', 'subjects.name as subject_name')
            ->join('subjects', 'exam_schedules.subject_id', '=', 'subjects.id')
            ->where('exam_schedules.class_id', $class_id)
            ->where('exam_schedules.exam_type', $exam_type)
            ->get();
        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_type' => 'required',
            'class_id' => 'required',
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required',
            'subjects.*.exam_date' => 'required|date|after_or_equal:today',
            'subjects.*.exam_time' => 'required',
            'subjects.*.end_time' => 'required',
            'subjects.*.max_marks' => 'required|numeric',
            'subjects.*.passing_marks' => 'required|numeric',
        ]);

        foreach ($request->subjects as $subjectData) {
            \App\Models\ExamSchedule::create([
                'exam_type' => $request->exam_type,
                'class_id' => $request->class_id,
                'subject_id' => $subjectData['subject_id'],
                'exam_date' => $subjectData['exam_date'],
                'exam_time' => $subjectData['exam_time'],
                'end_time' => $subjectData['end_time'],
                'max_marks' => $subjectData['max_marks'],
                'passing_marks' => $subjectData['passing_marks'],
                'school_id' => 1,
                'academic_year_id' => 1,
            ]);
        }

        return $this->ajaxSuccess($request, 'Exam event scheduled successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'exam_type' => 'required',
            'class_id' => 'required',
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required',
            'subjects.*.exam_date' => 'required|date|after_or_equal:today',
            'subjects.*.exam_time' => 'required',
            'subjects.*.end_time' => 'required',
            'subjects.*.max_marks' => 'required|numeric',
            'subjects.*.passing_marks' => 'required|numeric',
        ]);

        $exam = \App\Models\ExamSchedule::findOrFail($id);
        
        // Delete old event records
        \App\Models\ExamSchedule::where('class_id', $exam->class_id)
            ->where('exam_type', $exam->exam_type)
            ->delete();

        // Create new records
        foreach ($request->subjects as $subjectData) {
            \App\Models\ExamSchedule::create([
                'exam_type' => $request->exam_type,
                'class_id' => $request->class_id,
                'subject_id' => $subjectData['subject_id'],
                'exam_date' => $subjectData['exam_date'],
                'exam_time' => $subjectData['exam_time'],
                'end_time' => $subjectData['end_time'],
                'max_marks' => $subjectData['max_marks'],
                'passing_marks' => $subjectData['passing_marks'],
                'school_id' => 1,
                'academic_year_id' => 1,
            ]);
        }

        return $this->ajaxSuccess($request, 'Exam group updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $exam = \App\Models\ExamSchedule::findOrFail($id);
        
        if ($request->has('delete_group') && $request->delete_group == '1') {
            \App\Models\ExamSchedule::where('class_id', $exam->class_id)
                ->where('exam_type', $exam->exam_type)
                ->delete();
        } else {
            $exam->delete();
        }

        return $this->ajaxSuccess($request, 'Exam deleted successfully.');
    }

    public function marks()
    {
        return view('exams.marks');
    }
}
