<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalNote;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalLearningController extends Controller
{
    // --- NOTES ---
    public function notesIndex()
    {
        $notes = DigitalNote::with(['class', 'section', 'subject', 'academicYear', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->get();
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $academicYears = AcademicYear::all();

        return view('admin.digital_learning.notes', compact('notes', 'classes', 'sections', 'subjects', 'academicYears'));
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
            'section_id' => $request->section_id,
            'academic_year_id' => $request->academic_year_id,
            'uploaded_by' => auth()->id(),
            'is_public' => $request->has('is_public'),
            'school_id' => auth()->user()->school_id ?? 1,
        ]);

        return redirect()->back()->with('success', 'Digital Note created successfully.');
    }

    public function updateNote(Request $request, $id)
    {
        $note = DigitalNote::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt,jpg,png|max:10240',
            'external_url' => 'nullable|url',
        ]);

        if ($request->hasFile('file')) {
            if ($note->file_path && Storage::disk('public')->exists($note->file_path)) {
                Storage::disk('public')->delete($note->file_path);
            }
            $note->file_path = $request->file('file')->store('digital_notes', 'public');
            $extension = $request->file('file')->getClientOriginalExtension();
            if (in_array($extension, ['pdf'])) $note->file_type = 'pdf';
            elseif (in_array($extension, ['doc', 'docx'])) $note->file_type = 'doc';
            elseif (in_array($extension, ['ppt', 'pptx'])) $note->file_type = 'ppt';
            elseif (in_array($extension, ['jpg', 'png', 'jpeg'])) $note->file_type = 'image';
            else $note->file_type = 'text';
        }

        $note->update([
            'title' => $request->title,
            'description' => $request->description,
            'external_url' => $request->external_url,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'academic_year_id' => $request->academic_year_id,
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->back()->with('success', 'Digital Note updated successfully.');
    }

    public function destroyNote($id)
    {
        $note = DigitalNote::findOrFail($id);
        if ($note->file_path && Storage::disk('public')->exists($note->file_path)) {
            Storage::disk('public')->delete($note->file_path);
        }
        $note->delete();
        return redirect()->back()->with('success', 'Digital Note deleted successfully.');
    }

    // --- QUIZZES ---
    public function quizzesIndex()
    {
        $quizzes = Quiz::with(['class', 'section', 'subject', 'academicYear', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $academicYears = AcademicYear::all();

        return view('admin.digital_learning.quizzes', compact('quizzes', 'classes', 'sections', 'subjects', 'academicYears'));
    }

    public function storeQuiz(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'duration_minutes' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
        ]);

        Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'academic_year_id' => $request->academic_year_id,
            'created_by' => auth()->id(),
            'duration_minutes' => $request->duration_minutes,
            'passing_marks' => $request->passing_marks,
            'total_marks' => 0, // Calculated dynamically later based on questions
            'is_active' => $request->has('is_active'),
            'school_id' => auth()->user()->school_id ?? 1,
        ]);

        return redirect()->back()->with('success', 'Quiz created successfully.');
    }

    public function updateQuiz(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'duration_minutes' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
        ]);

        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'academic_year_id' => $request->academic_year_id,
            'duration_minutes' => $request->duration_minutes,
            'passing_marks' => $request->passing_marks,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Quiz updated successfully.');
    }

    public function destroyQuiz($id)
    {
        Quiz::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Quiz deleted successfully.');
    }

    // --- QUIZ QUESTIONS ---
    public function manageQuestions($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('admin.digital_learning.quiz_questions', compact('quiz'));
    }

    public function storeQuestion(Request $request, $quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'correct_option' => 'required|in:a,b,c,d',
            'marks' => 'required|integer|min:1',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => $request->question_text,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_option' => $request->correct_option,
            'marks' => $request->marks,
            'order' => $request->order ?? 1,
        ]);

        // Update total marks for quiz
        $quiz->increment('total_marks', $request->marks);

        return redirect()->back()->with('success', 'Question added successfully.');
    }

    public function updateQuestion(Request $request, $quiz_id, $question_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $question = QuizQuestion::where('quiz_id', $quiz->id)->findOrFail($question_id);
        
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'correct_option' => 'required|in:a,b,c,d',
            'marks' => 'required|integer|min:1',
        ]);

        $marksDifference = $request->marks - $question->marks;

        $question->update([
            'question_text' => $request->question_text,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_option' => $request->correct_option,
            'marks' => $request->marks,
            'order' => $request->order ?? $question->order,
        ]);

        if ($marksDifference != 0) {
            $quiz->increment('total_marks', $marksDifference);
        }

        return redirect()->back()->with('success', 'Question updated successfully.');
    }

    public function destroyQuestion($quiz_id, $question_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $question = QuizQuestion::where('quiz_id', $quiz->id)->findOrFail($question_id);
        
        $quiz->decrement('total_marks', $question->marks);
        $question->delete();

        return redirect()->back()->with('success', 'Question deleted successfully.');
    }

    // --- QUIZ RESULTS ---
    public function quizResults($id)
    {
        $quiz = Quiz::with(['class', 'section', 'subject'])->findOrFail($id);
        $attempts = QuizAttempt::with('student.user')
            ->where('quiz_id', $id)
            ->orderBy('score', 'desc')
            ->get();
            
        return view('admin.digital_learning.quiz_results', compact('quiz', 'attempts'));
    }
}
