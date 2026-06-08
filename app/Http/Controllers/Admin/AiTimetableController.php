<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AiTimetableGeneratorService;

class AiTimetableController extends Controller
{
    protected $timetableService;

    public function __construct(AiTimetableGeneratorService $timetableService)
    {
        $this->timetableService = $timetableService;
    }

    public function index()
    {
        return view('admin.ai.timetable');
    }

    public function generate(Request $request)
    {
        $result = $this->timetableService->generateTimetable();

        return response()->json($result);
    }
}
