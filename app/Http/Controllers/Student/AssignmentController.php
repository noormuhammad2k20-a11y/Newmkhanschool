<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $submissionCutoff = Carbon::now()->subDay();
        // A past-due assignment is visible for 1 full day after its deadline.
        // If deadline is June 11 (23:59:59), it should be visible all of June 12, and disappear on June 13.
        // So we keep if due_date >= (today - 1 day) at start of day.
        $dueDateCutoff = Carbon::now()->subDays(1)->startOfDay();

        // Get all submitted assignment IDs for this student
        $submittedIds = AssignmentSubmission::where('student_id', $student->id)
            ->pluck('assignment_id')
            ->toArray();

        $assignments = Assignment::with(['subject','teacher'])
            ->where('class_id', $student->current_class_id)
            // 1. Exclude submitted assignments where submission is older than 1 day
            ->whereNotIn('id', function($query) use ($student, $submissionCutoff) {
                $query->select('assignment_id')
                      ->from('assignment_submissions')
                      ->where('student_id', $student->id)
                      ->where('created_at', '<', $submissionCutoff);
            })
            // 2. Exclude unsubmitted assignments where due date is 1 day past deadline
            ->where(function($query) use ($submittedIds, $dueDateCutoff) {
                if (!empty($submittedIds)) {
                    $query->whereIn('id', $submittedIds)
                          ->orWhereDate('due_date', '>=', $dueDateCutoff);
                } else {
                    $query->whereDate('due_date', '>=', $dueDateCutoff);
                }
            })
            ->orderByDesc('due_date')
            ->paginate(15);

        // Calculate counts
        $totalAssignments = Assignment::where('class_id', $student->current_class_id)->count();
        $submittedCount = count($submittedIds);
        
        // Late means past due date and not submitted
        $lateCount = Assignment::where('class_id', $student->current_class_id)
            ->whereNotIn('id', $submittedIds)
            ->where('due_date', '<', Carbon::now())
            ->count();

        // Pending means not submitted and not late
        $pendingCount = Assignment::where('class_id', $student->current_class_id)
            ->whereNotIn('id', $submittedIds)
            ->where('due_date', '>=', Carbon::now())
            ->count();

        return view('student.assignments', compact('assignments','submittedIds','student', 'pendingCount', 'submittedCount', 'lateCount'));
    }

    public function submit(Request $request, $assignmentId)
    {
        $student    = auth()->user()->student;
        $assignment = Assignment::where('id', $assignmentId)
            ->where('class_id', $student->current_class_id)
            ->firstOrFail();

        $request->validate([
            'file'  => 'nullable|file|max:10240|extensions:pdf,doc,docx,jpg,jpeg,png,zip',
            'notes' => 'nullable|string|max:1000',
        ], [
            'file.extensions' => 'The file must be a file of type: pdf, doc, docx, jpg, jpeg, png, zip.',
        ]);

        $endOfDueDate = Carbon::parse($assignment->due_date)->endOfDay();
        if (Carbon::now()->gt($endOfDueDate)) {
            return back()->withErrors(['error' => 'The due date for this assignment has passed. You can no longer submit it.']);
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        if (AssignmentSubmission::where('assignment_id', $assignment->id)->where('student_id', $student->id)->exists()) {
            return back()->withErrors(['error' => 'You have already submitted this assignment and cannot submit it again.']);
        }

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'file_path' => $filePath,
            'notes'     => $request->notes,
            'status'    => Carbon::now()->gt($assignment->due_date) ? 'Late' : 'Submitted',
        ]);

        return back()->with('success', 'Assignment submitted successfully.');
    }
}
