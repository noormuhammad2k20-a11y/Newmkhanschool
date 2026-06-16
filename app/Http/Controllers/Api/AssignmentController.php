<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignmentSubmission;

class AssignmentController extends Controller
{
    /**
     * Mock AI Auto Grader Logic
     */
    public function autoGrade(Request $request)
    {
        $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'exists:assignment_submissions,id'
        ]);

        $successCount = 0;
        $results = [];

        foreach ($request->submission_ids as $subId) {
            $submission = AssignmentSubmission::find($subId);
            
            if ($submission) {
                // Mock AI logic: randomly generate marks between 50 and 100
                // In production, this would call an external ML service
                $aiScore = rand(50, 100);
                
                $submission->update([
                    'marks_obtained' => $aiScore,
                    'status' => 'graded',
                    'teacher_feedback' => 'AI Generated: Good effort on the assignment. Ensure citations are proper next time.'
                ]);
                
                $successCount++;
                $results[] = [
                    'submission_id' => $subId,
                    'score' => $aiScore,
                    'status' => 'success'
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "$successCount submissions auto-graded successfully via AI logic.",
            'data' => $results
        ]);
    }
}
