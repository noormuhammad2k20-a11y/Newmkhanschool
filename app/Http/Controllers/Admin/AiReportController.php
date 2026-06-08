<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AiReportGeneratorService;

class AiReportController extends Controller
{
    protected $reportService;

    public function __construct(AiReportGeneratorService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('admin.ai.reports');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:student_performance,attendance_trends,fee_collection'
        ]);

        try {
            $report = $this->reportService->generateReport($request->report_type);
            
            return response()->json([
                'status' => 'success',
                'data' => $report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
