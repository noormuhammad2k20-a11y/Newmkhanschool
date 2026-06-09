<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\OnlineExam;
use App\Models\ExamQuestion;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OnlineExamController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $exams   = OnlineExam::where('class_id', $student->current_class_id)
            ->whereIn('status', ['Published','Active','Closed'])
            ->with(['subject'])
            ->orderByDesc('exam_date')
            ->get();

        $attemptedIds = ExamAttempt::where('student_id', $student->id)
            ->pluck('exam_id')->toArray();

        $allAttempts = ExamAttempt::where('student_id', $student->id)->get();
        $averagePerformance = $allAttempts->count() > 0 ? round($allAttempts->avg('percentage'), 1) : 0;
        
        $upcomingExams = $exams->where('status', 'Published')->whereNotIn('id', $attemptedIds)->count();
        $activeExams = $exams->where('status', 'Active')->whereNotIn('id', $attemptedIds)->count();
        $completedExams = count($attemptedIds);

        return view('student.online-exams.index', compact('exams','attemptedIds', 'upcomingExams', 'activeExams', 'completedExams', 'averagePerformance', 'allAttempts'));
    }

    public function start($examId)
    {
        $student = auth()->user()->student;
        $exam    = OnlineExam::where('id', $examId)
            ->where('class_id', $student->current_class_id)
            ->where('status', 'Published')
            ->firstOrFail();

        // Check if already attempted
        $existing = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)->first();
        if ($existing && $existing->status !== 'Not Started') {
            return redirect()->route('student.online-exams.result', $examId)
                ->with('info', 'You have already submitted this exam.');
        }

        // Create attempt record
        $attempt = ExamAttempt::updateOrCreate(
            ['exam_id' => $exam->id, 'student_id' => $student->id],
            ['started_at' => now(), 'status' => 'In Progress', 'ip_address' => request()->ip()]
        );

        $questions = ExamQuestion::where('exam_id', $exam->id)
            ->when($exam->shuffle_questions, fn($q) => $q->inRandomOrder())
            ->get();

        return view('student.online-exams.take', compact('exam','questions','attempt'));
    }

    public function submit(Request $request, $examId)
    {
        $student = auth()->user()->student;
        $exam    = OnlineExam::findOrFail($examId);
        $attempt = ExamAttempt::where('exam_id', $examId)
            ->where('student_id', $student->id)
            ->where('status', 'In Progress')
            ->firstOrFail();

        $questions  = ExamQuestion::where('exam_id', $exam->id)->get();
        $totalMarks = 0;
        $obtained   = 0;

        foreach ($questions as $question) {
            $studentAnswer = $request->input("answers.{$question->id}");
            $isCorrect     = null;
            $marksAwarded  = 0;

            if ($question->question_type !== 'Short') {
                $isCorrect = strtolower(trim($studentAnswer ?? '')) === strtolower(trim($question->correct_answer ?? ''));
                $marksAwarded = $isCorrect ? $question->marks : 0;
            }
            // Short answer requires manual grading — award 0 for now

            $totalMarks += $question->marks;
            $obtained   += $marksAwarded;

            ExamAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $question->id],
                [
                    'student_answer' => $studentAnswer,
                    'is_correct'     => $isCorrect,
                    'marks_awarded'  => $marksAwarded,
                ]
            );
        }

        $pct = $totalMarks > 0 ? round(($obtained / $totalMarks) * 100, 1) : 0;

        $attempt->update([
            'submitted_at'   => now(),
            'total_marks'    => $totalMarks,
            'obtained_marks' => $obtained,
            'percentage'     => $pct,
            'status'         => 'Submitted',
        ]);

        if ($exam->show_result_immediately) {
            return redirect()->route('student.online-exams.result', $examId)
                ->with('success', 'Exam submitted! Your result is ready.');
        }

        return redirect()->route('student.online-exams.index')
            ->with('success', 'Exam submitted successfully. Result will be announced soon.');
    }

    public function result($examId)
    {
        $student = auth()->user()->student;
        $exam    = OnlineExam::with('subject')->findOrFail($examId);
        $attempt = ExamAttempt::where('exam_id', $examId)
            ->where('student_id', $student->id)->firstOrFail();
        $answers  = ExamAnswer::with('question')
            ->where('attempt_id', $attempt->id)->get();

        return view('student.online-exams.result', compact('exam','attempt','answers'));
    }
}
