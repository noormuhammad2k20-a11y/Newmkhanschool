<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DigitalNote;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Student;
use Illuminate\Http\Request;

class DigitalLearningController extends Controller
{
    public function notesIndex()
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();

        $notes = DigitalNote::with(['subject', 'uploader'])
            ->where('is_public', 1)
            ->where('class_id', $student->current_class_id)
            ->where(function($q) use ($student) {
                $q->whereNull('section_id')
                  ->orWhere('section_id', $student->current_section_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalNotes = $notes->count();
        $subjectsCovered = $notes->pluck('subject_id')->unique()->count();
        $downloadedNotes = 0; // Simulated
        $pendingNotes = $totalNotes;

        return view('student.digital_learning.notes', compact('notes', 'totalNotes', 'subjectsCovered', 'downloadedNotes', 'pendingNotes'));
    }

    public function quizzesIndex()
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();

        $quizzes = Quiz::with(['subject', 'creator'])
            ->where('is_active', 1)
            ->where('class_id', $student->current_class_id)
            ->where(function($q) use ($student) {
                $q->whereNull('section_id')
                  ->orWhere('section_id', $student->current_section_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get student's previous attempts
        $attempts = QuizAttempt::where('student_id', $student->id)->get()->keyBy('quiz_id');

        $availableQuizzes = $quizzes->count();
        $completedQuizzes = $attempts->count();
        $averageScore = $attempts->count() > 0 ? round($attempts->avg('percentage'), 1) : 0;
        $upcomingQuizzes = max(0, $availableQuizzes - $completedQuizzes);

        return view('student.digital_learning.quizzes', compact('quizzes', 'attempts', 'availableQuizzes', 'completedQuizzes', 'averageScore', 'upcomingQuizzes'));
    }

    public function takeQuiz($id)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        $quiz = Quiz::with('questions')->where('is_active', 1)->findOrFail($id);

        // Check if student already attempted
        $existingAttempt = QuizAttempt::where('quiz_id', $id)->where('student_id', $student->id)->first();
        if ($existingAttempt) {
            return redirect()->route('student.digital_learning.quizzes')->with('error', 'You have already attempted this quiz.');
        }

        return view('student.digital_learning.take_quiz', compact('quiz'));
    }

    public function submitQuiz(Request $request, $id)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        $quiz = Quiz::with('questions')->where('is_active', 1)->findOrFail($id);

        // Check if student already attempted
        $existingAttempt = QuizAttempt::where('quiz_id', $id)->where('student_id', $student->id)->first();
        if ($existingAttempt) {
            return redirect()->route('student.digital_learning.quizzes')->with('error', 'You have already attempted this quiz.');
        }

        $answers = $request->input('answers', []);
        $score = 0;

        foreach ($quiz->questions as $question) {
            $studentAnswer = $answers[$question->id] ?? null;
            if ($studentAnswer === $question->correct_option) {
                $score += $question->marks;
            }
        }

        $percentage = $quiz->total_marks > 0 ? ($score / $quiz->total_marks) * 100 : 0;

        QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'score' => $score,
            'total_marks' => $quiz->total_marks,
            'percentage' => $percentage,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.digital_learning.quizzes')->with('success', 'Quiz submitted successfully. Your score: ' . $score . '/' . $quiz->total_marks);
    }
}
