<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DigitalNote;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalLearningController extends Controller
{
    public function notes()
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (!$student) return redirect()->back()->with('error', 'Student profile not found.');

        $notes = DigitalNote::with(['subject', 'uploader'])
            ->where('class_id', $student->class_id)
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('student.digital_learning.notes', compact('notes'));
    }

    public function downloadNote($id)
    {
        $note = DigitalNote::findOrFail($id);
        $note->increment('download_count');
        
        if ($note->file_path && Storage::disk('public')->exists($note->file_path)) {
            return Storage::disk('public')->download($note->file_path);
        }
        
        if ($note->external_url) {
            return redirect($note->external_url);
        }

        return redirect()->back()->with('error', 'File not found.');
    }

    public function quizzes()
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (!$student) return redirect()->back()->with('error', 'Student profile not found.');

        $quizzes = Quiz::with('subject')
            ->where('class_id', $student->class_id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $attempts = QuizAttempt::where('student_id', $student->id)->get()->keyBy('quiz_id');

        return view('student.digital_learning.quizzes', compact('quizzes', 'attempts'));
    }

    public function showQuiz($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('student.digital_learning.quiz_show', compact('quiz'));
    }

    public function submitQuiz(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $student = Student::where('user_id', auth()->id())->first();
        
        $score = 0;
        foreach ($quiz->questions as $question) {
            $answer = $request->input('q_' . $question->id);
            if ($answer == $question->correct_option) {
                $score += $question->marks;
            }
        }
        
        $percentage = ($quiz->total_marks > 0) ? ($score / $quiz->total_marks) * 100 : 0;
        
        QuizAttempt::updateOrCreate(
            ['quiz_id' => $quiz->id, 'student_id' => $student->id],
            [
                'score' => $score,
                'total_marks' => $quiz->total_marks,
                'percentage' => $percentage,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]
        );
        
        return redirect()->route('student.quizzes')->with('success', 'Quiz submitted successfully! Score: ' . $score . '/' . $quiz->total_marks);
    }
}
