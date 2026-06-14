<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentPortfolioController extends Controller
{
    public function show($studentId)
    {
        $portfolio = \App\Models\StudentPortfolio::where('student_id', $studentId)->first();
        
        if (!$portfolio) {
            abort(404, 'Portfolio not found');
        }

        // Privacy control logic
        $user = auth()->user();
        if ($portfolio->visibility === 'parent_only') {
            if (!$user || ($user->role->name !== 'admin' && !($user->role->name === 'parent' && $user->parent->children->contains('id', $studentId)))) {
                abort(403, 'Unauthorized to view this portfolio');
            }
        } elseif ($portfolio->visibility === 'school_only') {
            if (!$user) {
                abort(403, 'Unauthorized to view this portfolio');
            }
        }

        $items = $portfolio->items()->get();

        return view('public.portfolio', compact('portfolio', 'items'));
    }

    public function myPortfolio()
    {
        $studentId = auth()->user()->student->id ?? null;
        if (!$studentId) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        $portfolio = \App\Models\StudentPortfolio::firstOrCreate(
            ['student_id' => $studentId],
            ['title' => auth()->user()->name . '\'s Portfolio', 'visibility' => 'school_only']
        );

        $items = $portfolio->items()->get();

        return view('student.portfolio.index', compact('portfolio', 'items'));
    }
    public function downloadResume($studentId)
    {
        $portfolio = \App\Models\StudentPortfolio::with(['student', 'items'])->where('student_id', $studentId)->firstOrFail();

        // In a real application, you would use DOMPDF
        // $pdf = \PDF::loadView('public.resume_pdf', compact('portfolio'));
        // return $pdf->download("resume_{$portfolio->student->admission_no}.pdf");

        return redirect()->back()->with('success', 'Resume PDF generated successfully. (DOMPDF Placeholder)');
    }
}
