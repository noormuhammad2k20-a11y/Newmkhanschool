<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\DigitalNote;
use App\Models\Quiz;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;

class DigitalLearningController extends Controller
{
    public function index()
    {
        $teacher = \App\Models\Teacher::where('user_id', auth()->id())->first();
        if (!$teacher) return redirect()->back()->with('error', 'Teacher profile not found.');

        // Get assigned classes and subjects
        $assignments = TeacherAssignment::where('teacher_id', $teacher->id)->with(['class', 'subject'])->get();
        
        $classes = $assignments->pluck('class')->unique('id');
        $subjects = $assignments->pluck('subject')->unique('id');

        $notes = DigitalNote::with(['class', 'subject'])->where('uploaded_by', auth()->id())->orderBy('created_at', 'desc')->get();
        $quizzes = Quiz::with(['class', 'subject'])->where('created_by', auth()->id())->orderBy('created_at', 'desc')->get();

        return view('teacher.digital_learning.index', compact('notes', 'quizzes', 'classes', 'subjects'));
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt,jpg,png|max:10240',
            'external_url' => 'nullable|url',
        ]);

        $filePath = null;
        $fileType = 'link';

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('digital_notes', 'public');
            $extension = $request->file('file')->getClientOriginalExtension();
            if (in_array($extension, ['pdf'])) $fileType = 'pdf';
            elseif (in_array($extension, ['doc', 'docx'])) $fileType = 'doc';
            elseif (in_array($extension, ['ppt', 'pptx'])) $fileType = 'ppt';
            elseif (in_array($extension, ['jpg', 'png', 'jpeg'])) $fileType = 'image';
            else $fileType = 'text';
        }

        DigitalNote::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'external_url' => $request->external_url,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'uploaded_by' => auth()->id(),
            'school_id' => auth()->user()->school_id ?? 1,
        ]);

        return redirect()->back()->with('success', 'Digital Note uploaded successfully.');
    }

    public function storeQuiz(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'duration_minutes' => 'required|integer|min:5',
        ]);

        Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'created_by' => auth()->id(),
            'duration_minutes' => $request->duration_minutes,
            'total_marks' => $request->total_marks ?? 10,
            'is_active' => $request->has('is_active'),
            'school_id' => auth()->user()->school_id ?? 1,
        ]);

        return redirect()->back()->with('success', 'Quiz created successfully.');
    }
}
