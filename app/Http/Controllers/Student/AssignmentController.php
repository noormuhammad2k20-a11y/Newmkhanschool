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

        $assignments = Assignment::with(['subject','teacher'])
            ->where('class_id', $student->current_class_id)
            ->orderByDesc('due_date')
            ->paginate(15);

        // Attach submission status for each assignment
        $submittedIds = AssignmentSubmission::where('student_id', $student->id)
            ->pluck('assignment_id')
            ->toArray();

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
            'file'  => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,png,zip',
            'notes' => 'nullable|string|max:1000',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            [
                'file_path' => $filePath,
                'notes'     => $request->notes,
                'status'    => Carbon::now()->gt($assignment->due_date) ? 'Late' : 'Submitted',
            ]
        );

        return back()->with('success', 'Assignment submitted successfully.');
    }
}
