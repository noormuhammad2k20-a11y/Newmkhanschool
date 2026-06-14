<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportCard;
use App\Models\ReportCardNarrative;

class ReportCardNarrativeController extends Controller
{
    protected $aiService;

    public function __construct(\App\Services\AiReportCardNarrativeService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generate(Request $request, $reportCardId)
    {
        $reportCard = ReportCard::findOrFail($reportCardId);
        
        try {
            $narrative = $this->aiService->generateForReportCard($reportCard);
            return response()->json(['success' => true, 'data' => $narrative]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function batchGenerate(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'nullable|exists:classes,id'
        ]);

        \App\Jobs\BatchGenerateNarrativesJob::dispatch($request->academic_year_id, $request->class_id);

        return response()->json(['success' => true, 'message' => 'Batch generation started in the background.']);
    }

    public function lock($id)
    {
        $narrative = ReportCardNarrative::findOrFail($id);
        $narrative->update(['is_locked' => true]);
        return response()->json(['success' => true, 'message' => 'Narrative locked successfully.']);
    }

    public function exportPdf($id)
    {
        $narrative = ReportCardNarrative::with('reportCard.student')->findOrFail($id);
        
        // Placeholder for DOMPDF integration
        // $pdf = \PDF::loadView('admin.report_cards.narrative_pdf', compact('narrative'));
        // return $pdf->download("narrative-{$narrative->reportCard->student->admission_no}.pdf");
        
        return redirect()->back()->with('success', 'PDF exported successfully. (DOMPDF Placeholder)');
    }

    public function update(Request $request, $id)
    {
        $narrative = ReportCardNarrative::findOrFail($id);
        
        if ($narrative->is_locked) {
            return response()->json(['success' => false, 'message' => 'Cannot edit a locked narrative.'], 403);
        }

        $validated = $request->validate([
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'attendance_summary' => 'nullable|string',
            'teacher_comments' => 'nullable|string',
            'parent_guidance' => 'nullable|string',
            'next_term_goals' => 'nullable|string',
        ]);

        $narrative->update($validated);

        return response()->json(['success' => true, 'data' => $narrative]);
    }
}
