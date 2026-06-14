<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentBadgeController extends Controller
{
    public function index()
    {
        $studentId = auth()->user()->student->id ?? null; // Adjust depending on your auth logic
        
        if (!$studentId) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        $badges = \App\Models\StudentBadge::where('student_id', $studentId)
                        ->orderBy('awarded_at', 'desc')
                        ->get();

        $certificates = \App\Models\IssuedDocument::where('student_id', $studentId)
                            ->orderBy('issue_date', 'desc')
                            ->get();

        return view('student.achievements.index', compact('badges', 'certificates'));
    }
}
