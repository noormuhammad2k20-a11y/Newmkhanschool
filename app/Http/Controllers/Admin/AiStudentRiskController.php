<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AiStudentRiskAnalysisService;
use App\Models\SchoolClass;

class AiStudentRiskController extends Controller
{
    protected $riskService;

    public function __construct(AiStudentRiskAnalysisService $riskService)
    {
        $this->riskService = $riskService;
    }

    public function index(Request $request)
    {
        $classes = SchoolClass::all();
        $selectedClassId = $request->input('class_id');
        
        $riskProfiles = $this->riskService->analyzeRisk($selectedClassId);

        // Aggregate statistics for the dashboard
        $highRisk = count(array_filter($riskProfiles, fn($p) => $p['risk_level'] === 'High'));
        $mediumRisk = count(array_filter($riskProfiles, fn($p) => $p['risk_level'] === 'Medium'));
        $lowRisk = count(array_filter($riskProfiles, fn($p) => $p['risk_level'] === 'Low'));

        return view('admin.ai.risk', compact('classes', 'selectedClassId', 'riskProfiles', 'highRisk', 'mediumRisk', 'lowRisk'));
    }

    public function analyze(Request $request)
    {
        $classId = $request->input('class_id');
        $riskProfiles = $this->riskService->analyzeRisk($classId);

        return response()->json([
            'status' => 'success',
            'data' => $riskProfiles
        ]);
    }
}
