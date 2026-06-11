<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Services\AIGraderService;
use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Http\Request;

class AIGraderController extends Controller
{
    use AjaxResponseTrait;
    protected $aiGrader;

    public function __construct(AIGraderService $aiGrader)
    {
        $this->aiGrader = $aiGrader;
    }

    public function index()
    {
        $teacher = auth()->user()->teacher;
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->withCount(['submissions as pending_submissions_count' => function ($query) {
                $query->whereIn('status', ['Submitted', 'Late', 'submitted', 'late']);
            }])
            ->having('pending_submissions_count', '>', 0)
            ->get();

        return view('teacher.ai-grader.index', compact('assignments'));
    }

    public function showSubmissions($assignmentId)
    {
        $teacher = auth()->user()->teacher;
        $assignment = Assignment::where('teacher_id', $teacher->id)->findOrFail($assignmentId);
        
        $submissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->with(['student', 'aiGradingResult'])
            ->get();

        return view('teacher.assignments.submissions', compact('assignment', 'submissions'));
    }

    public function gradeWithAI(Request $request, $submissionId)
    {
        $teacher = auth()->user()->teacher;
        
        // Ensure submission belongs to an assignment owned by this teacher
        $submission = AssignmentSubmission::whereHas('assignment', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->findOrFail($submissionId);

        $result = $this->aiGrader->gradeSubmission($submission->id);

        if ($result['status'] === 'success') {
            return $this->ajaxSuccess($request, 'Submission graded successfully using AI.');
        } else {
            return $this->ajaxError($request, $result['message']);
        }
    }

    public function bulkGradeWithAI(Request $request, $assignmentId)
    {
        $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'exists:assignment_submissions,id'
        ]);

        $teacher = auth()->user()->teacher;
        $assignment = Assignment::where('teacher_id', $teacher->id)->findOrFail($assignmentId);

        $successCount = 0;
        $errors = [];

        foreach ($request->submission_ids as $subId) {
            $submission = AssignmentSubmission::where('assignment_id', $assignment->id)->find($subId);
            if ($submission) {
                $result = $this->aiGrader->gradeSubmission($submission->id);
                if ($result['status'] === 'success') {
                    $successCount++;
                } else {
                    $errors[] = "Failed for Student ID {$submission->student_id}: " . $result['message'];
                }
            }
        }

        if (empty($errors)) {
            return $this->ajaxSuccess($request, "$successCount submissions bulk graded using AI.");
        } else {
            return $this->ajaxError($request, "$successCount graded. Errors: " . implode(' | ', $errors));
        }
    }

    public function applyGrade(Request $request, $submissionId)
    {
        $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:100',
            'teacher_feedback' => 'nullable|string'
        ]);

        $teacher = auth()->user()->teacher;
        $submission = AssignmentSubmission::whereHas('assignment', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->findOrFail($submissionId);

        $submission->update([
            'marks_obtained' => $request->marks_obtained,
            'teacher_feedback' => $request->teacher_feedback,
            'status' => 'graded'
        ]);

        return $this->ajaxSuccess($request, 'Final grade saved successfully.');
    }
}
