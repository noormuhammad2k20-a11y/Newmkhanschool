<?php
namespace App\Http\Controllers\ParentPortal;

use App\Models\Student;
use App\Models\ExamAttempt;

class OnlineExamController extends BaseParentController
{
    public function index($student_id)
    {
        abort_unless($this->parentOwnsStudent($student_id), 403);
        $student  = Student::findOrFail($student_id);
        $attempts = ExamAttempt::with('onlineExam.subject')
            ->where('student_id', $student_id)
            ->whereNotNull('submitted_at')
            ->orderByDesc('submitted_at')->get();
        return view('parent.online-exams.index', compact('student', 'attempts'));
    }

    public function result($student_id, $exam_id)
    {
        abort_unless($this->parentOwnsStudent($student_id), 403);
        $attempt = ExamAttempt::with('onlineExam.subject', 'answers.question')
            ->where('student_id', $student_id)
            ->where('online_exam_id', $exam_id)
            ->firstOrFail();
        return view('parent.online-exams.result', compact('attempt'));
    }
}
