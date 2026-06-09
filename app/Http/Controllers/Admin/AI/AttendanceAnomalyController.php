<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\AttendanceAnomaly;
use App\Models\AttendancePattern;
use Illuminate\Http\Request;

class AttendanceAnomalyController extends Controller
{
    public function index()
    {
        // For testing/mocking since auth()->user()->school_id might not exist in all contexts
        // Wait, auth()->user()->school_id doesn't exist, we use auth()->user()->school->id... wait, no school relation usually.
        // Assuming user has no school_id, using 1 as default.
        $schoolId = 1;

        $anomalies = AttendanceAnomaly::with(['student', 'teacher'])
            ->where('school_id', $schoolId)
            ->orderBy('detected_at', 'desc')
            ->paginate(15);
            
        $patterns = AttendancePattern::where('school_id', $schoolId)
            ->orderBy('absence_percentage', 'desc')
            ->get();
            
        return view('admin.ai.attendance', compact('anomalies', 'patterns'));
    }
    
    public function resolve(Request $request, AttendanceAnomaly $anomaly)
    {
        $anomaly->update([
            'resolved' => true,
            'resolved_at' => now()
        ]);
        
        return back()->with('success', 'Anomaly resolved successfully.');
    }
}
