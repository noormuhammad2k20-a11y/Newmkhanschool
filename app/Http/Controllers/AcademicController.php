<?php

namespace App\Http\Controllers;

use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherAssignment;

class AcademicController extends Controller
{
    use AjaxResponseTrait;
    public function index()
    {
        $classes = SchoolClass::all();
        $sections = \App\Models\Section::all();
        $subjects = Subject::select('subjects.*', 'classes.name as class_name')
            ->leftJoin('classes', 'subjects.class_id', '=', 'classes.id')
            ->get();
            
        $teachers = Teacher::all();
        $assignments = TeacherAssignment::with(['teacher', 'class_', 'subject'])->get();

        // Build matrix data: $matrixData[subject_id][class_id] = teacher_name
        $matrixData = [];
        foreach ($assignments as $assignment) {
            if ($assignment->teacher) {
                $matrixData[$assignment->subject_id][$assignment->class_id] = $assignment->teacher->full_name;
            }
        }

        return view('admin.academics.index', compact('classes', 'sections', 'subjects', 'teachers', 'assignments', 'matrixData'));
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sections' => 'nullable|string',
            'subjects' => 'nullable|string',
        ]);

        $class = new SchoolClass();
        $class->name = $request->name;
        $class->school_id = 1; // Assuming default school_id as 1 for now if single-school
        $class->save();

        if ($request->sections) {
            $sections = array_map('trim', explode(',', $request->sections));
            foreach(array_filter($sections) as $sectionName) {
                \App\Models\Section::create([
                    'name' => $sectionName,
                    'class_id' => $class->id,
                    'capacity' => 40,
                    'status' => 'active'
                ]);
            }
        }

        if ($request->subjects) {
            $subjects = array_map('trim', explode(',', $request->subjects));
            foreach(array_filter($subjects) as $subName) {
                Subject::create([
                    'name' => $subName,
                    'class_id' => $class->id
                ]);
            }
        }

        return $this->ajaxSuccess($request, 'Class structure added successfully.');
    }

    public function destroyClass(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);
        // Also delete related subjects and sections
        Subject::where('class_id', $class->id)->delete();
        \App\Models\Section::where('class_id', $class->id)->delete();
        $class->delete();

        return $this->ajaxSuccess($request, 'Class and related data deleted successfully.');
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'nullable|string|max:50',
            'class_id' => 'required|exists:classes,id',
        ]);

        $subjectNames = array_map('trim', explode(',', $request->name));
        
        foreach (array_filter($subjectNames) as $subName) {
            $subject = new Subject();
            $subject->name = $subName;
            $subject->code = count($subjectNames) === 1 ? $request->code : null; // Only apply code if it's a single subject
            $subject->class_id = $request->class_id;
            $subject->save();
        }

        return $this->ajaxSuccess($request, 'Subject(s) added successfully.');
    }

    public function updateSubject(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->name = $request->name;
        $subject->code = $request->code;
        $subject->save();

        return $this->ajaxSuccess($request, 'Subject updated successfully.');
    }

    public function destroySubject(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return $this->ajaxSuccess($request, 'Subject deleted successfully.');
    }

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        // Check if already assigned
        $exists = TeacherAssignment::where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        if ($exists) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This class and subject is already assigned. Please remove the existing assignment first.'], 422);
            }
            return redirect()->back()->withErrors(['message' => 'This class and subject is already assigned. Please remove the existing assignment first.']);
        }

        $assignment = new TeacherAssignment();
        $assignment->teacher_id = $request->teacher_id;
        $assignment->class_id = $request->class_id;
        $assignment->subject_id = $request->subject_id;
        $assignment->save();

        return $this->ajaxSuccess($request, 'Teacher assigned successfully.');
    }

    public function destroyAssignment(Request $request, $id)
    {
        $assignment = TeacherAssignment::findOrFail($id);
        $assignment->delete();

        return $this->ajaxSuccess($request, 'Assignment removed successfully.');
    }
}
