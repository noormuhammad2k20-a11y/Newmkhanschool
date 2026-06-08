<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AiAttendancePredictionService;
use App\Models\SchoolClass;

class AiAttendanceController extends Controller
{
    protected $predictionService;

    public function __construct(AiAttendancePredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    public function index(Request $request)
    {
        $classes = SchoolClass::all();
        $selectedClassId = $request->input('class_id');
        
        $trends = $this->predictionService->getSystemWideTrends();
        $predictions = $this->predictionService->predictClassAttendance($selectedClassId);

        return view('admin.ai.attendance', compact('classes', 'selectedClassId', 'trends', 'predictions'));
    }

    public function predict(Request $request)
    {
        $classId = $request->input('class_id');
        $predictions = $this->predictionService->predictClassAttendance($classId);

        return response()->json([
            'status' => 'success',
            'data' => $predictions
        ]);
    }
}
