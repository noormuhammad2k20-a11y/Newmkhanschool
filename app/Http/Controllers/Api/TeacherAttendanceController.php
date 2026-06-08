<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherLeave;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
            $today = Carbon::today()->toDateString();
            
            // Stats
            $totalPresent = TeacherAttendance::where('date', $today)->where('status', 'P')->count();
            
            $onLeave = TeacherLeave::where('status', 'Approved')
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->count();
                
            $lateArrivals = TeacherAttendance::where('date', $today)->where('status', 'L')->count();

            // Live Attendance Log
            $attendanceLog = TeacherAttendance::with('teacher')
                ->where('date', $today)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($log) {
                    return [
                        'id' => $log->id,
                        'teacher_name' => $log->teacher ? $log->teacher->full_name : 'Unknown',
                        'department' => $log->teacher ? $log->teacher->specialization : 'N/A',
                        'time' => $log->created_at ? $log->created_at->format('h:i A') : 'N/A',
                        'status' => $log->status
                    ];
                });

            // Pending Leaves
            $pendingLeaves = TeacherLeave::with('teacher')
                ->where('status', 'Pending')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($leave) {
                    return [
                        'id' => $leave->id,
                        'teacher_name' => $leave->teacher ? $leave->teacher->full_name : 'Unknown',
                        'leave_type' => $leave->leave_type,
                        'duration' => Carbon::parse($leave->start_date)->format('M d') . ' - ' . Carbon::parse($leave->end_date)->format('M d') . ' (' . (Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1) . ' Days)',
                        'status' => $leave->status
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'stats' => [
                        'present' => $totalPresent,
                        'on_leave' => $onLeave,
                        'late' => $lateArrivals
                    ],
                    'logs' => $attendanceLog,
                    'pending_leaves' => $pendingLeaves
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateLeaveStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected'
        ]);

        try {
            $leave = TeacherLeave::findOrFail($id);
            $leave->status = $request->status;
            $leave->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Leave status updated to ' . $request->status
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
