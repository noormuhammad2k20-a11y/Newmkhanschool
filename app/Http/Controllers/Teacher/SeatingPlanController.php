<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SeatingPlan;
use App\Models\SeatingAssignment;
use App\Models\TeacherAssignment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeatingPlanController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $plans = SeatingPlan::with(['class', 'section'])
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->get();

        return view('teacher.seating.index', compact('plans'));
    }

    public function create()
    {
        $teacher = auth()->user()->teacher;
        $classIds = TeacherAssignment::where('teacher_id', $teacher->id)->pluck('class_id')->unique();
        
        $classes = \App\Models\SchoolClass::whereIn('id', $classIds)->orderBy('name')->get();
        $sections = \App\Models\Section::whereIn('class_id', $classIds)->orderBy('name')->get();
            
        return view('teacher.seating.create', compact('classes', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'class_section_id' => 'required|string',
            'rows' => 'required|integer|min:1|max:20',
            'cols' => 'required|integer|min:1|max:20',
            'mode' => 'nullable|in:Regular,Exam'
        ]);

        $teacher = auth()->user()->teacher;
        list($classId, $sectionId) = explode('_', $request->class_section_id);

        $plan = SeatingPlan::create([
            'name' => $request->name,
            'class_id' => $classId,
            'section_id' => $sectionId,
            'teacher_id' => $teacher->id,
            'rows' => $request->rows,
            'cols' => $request->cols,
            'mode' => $request->mode ?? 'Regular',
            'school_id' => auth()->user()->school_id,
        ]);

        return redirect()->route('teacher.seating.edit', $plan->id)->with('success', 'Seating plan created. You can now assign seats.');
    }

    public function edit($id)
    {
        $teacher = auth()->user()->teacher;
        $plan = SeatingPlan::where('teacher_id', $teacher->id)->findOrFail($id);
        
        $students = Student::where('status', 'Active')
            ->where('current_class_id', $plan->class_id)
            ->where('current_section_id', $plan->section_id)
            ->get();
            
        $assignments = $plan->assignments()->with('student')->get();
        
        // Map assignments by [row][col]
        $grid = [];
        foreach($assignments as $assign) {
            $grid[$assign->row_num][$assign->col_num] = $assign->student;
        }

        // Get unassigned students
        $assignedStudentIds = $assignments->pluck('student_id')->toArray();
        $unassignedStudents = $students->filter(fn($s) => !in_array($s->id, $assignedStudentIds));

        return view('teacher.seating.edit', compact('plan', 'grid', 'unassignedStudents', 'students'));
    }

    public function updateGrid(Request $request, $id)
    {
        $teacher = auth()->user()->teacher;
        $plan = SeatingPlan::where('teacher_id', $teacher->id)->findOrFail($id);

        $request->validate([
            'assignments' => 'array',
            'assignments.*.student_id' => 'required|exists:students,id',
            'assignments.*.row' => 'required|integer',
            'assignments.*.col' => 'required|integer',
        ]);

        DB::transaction(function () use ($plan, $request) {
            $plan->assignments()->delete();
            
            if ($request->has('assignments')) {
                foreach($request->assignments as $assign) {
                    SeatingAssignment::create([
                        'seating_plan_id' => $plan->id,
                        'student_id' => $assign['student_id'],
                        'row_num' => $assign['row'],
                        'col_num' => $assign['col']
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Seating plan updated successfully.']);
    }

    public function autoArrange(Request $request, $id)
    {
        $teacher = auth()->user()->teacher;
        $plan = SeatingPlan::where('teacher_id', $teacher->id)->findOrFail($id);
        
        $students = Student::where('status', 'Active')
            ->where('current_class_id', $plan->class_id)
            ->where('current_section_id', $plan->section_id)
            ->orderBy('admission_no') // Roll number based seating
            ->get();

        if ($students->count() > ($plan->rows * $plan->cols)) {
            return response()->json(['error' => 'Not enough seats for all students.'], 400);
        }

        DB::transaction(function () use ($plan, $students) {
            $plan->assignments()->delete();
            
            $studentIndex = 0;
            $studentsArray = $students->values();

            for ($r = 1; $r <= $plan->rows; $r++) {
                for ($c = 1; $c <= $plan->cols; $c++) {
                    if ($studentIndex >= $studentsArray->count()) {
                        break 2;
                    }

                    // Separation rule for Exam mode: skip every other seat if enough space
                    if ($plan->mode === 'Exam') {
                        $totalSeats = $plan->rows * $plan->cols;
                        $halfSeats = floor($totalSeats / 2);
                        if ($studentsArray->count() <= $halfSeats) {
                            if (($r + $c) % 2 === 0) {
                                continue;
                            }
                        }
                    }

                    SeatingAssignment::create([
                        'seating_plan_id' => $plan->id,
                        'student_id' => $studentsArray[$studentIndex]->id,
                        'row_num' => $r,
                        'col_num' => $c
                    ]);
                    $studentIndex++;
                }
            }
        });

        return response()->json(['message' => 'Auto arrangement completed successfully.']);
    }

    public function show($id)
    {
        $teacher = auth()->user()->teacher;
        $plan = SeatingPlan::with(['class', 'section'])->where('teacher_id', $teacher->id)->findOrFail($id);
        
        $assignments = $plan->assignments()->with('student')->get();
        
        $grid = [];
        foreach($assignments as $assign) {
            $grid[$assign->row_num][$assign->col_num] = $assign->student;
        }

        return view('teacher.seating.show', compact('plan', 'grid'));
    }

    public function destroy($id)
    {
        $teacher = auth()->user()->teacher;
        $plan = SeatingPlan::where('teacher_id', $teacher->id)->findOrFail($id);
        $plan->delete();

        return redirect()->route('teacher.seating.index')->with('success', 'Seating plan deleted.');
    }
}
