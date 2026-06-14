<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherLeaveRequest;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
            $today = Carbon::today()->toDateString();
            
            // Stats
            $totalPresent = TeacherAttendance::where('date', $today)->where('status', 'P')->count();
            
            $onLeave = TeacherLeaveRequest::where('status', 'Approved')
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
            $pendingLeaves = TeacherLeaveRequest::with('teacher')
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
            $leave = TeacherLeaveRequest::findOrFail($id);
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
    public function roster(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::today()->toDateString());
            $teachers = Teacher::all();
            $attendances = TeacherAttendance::where('date', $date)->get()->keyBy('teacher_id');

            $roster = $teachers->map(function($teacher) use ($attendances) {
                $attendance = $attendances->get($teacher->id);
                return [
                    'id' => $teacher->id,
                    'full_name' => $teacher->full_name,
                    'employee_number' => $teacher->employee_number,
                    'status' => $attendance ? $attendance->status : null,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $roster
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.teacher_id' => 'required|exists:teachers,id',
            'attendances.*.status' => 'required|in:P,A,L,HD',
        ]);

        try {
            $date = $request->date;
            $attendances = $request->attendances;

            foreach ($attendances as $record) {
                TeacherAttendance::updateOrCreate(
                    ['teacher_id' => $record['teacher_id'], 'date' => $date],
                    ['status' => $record['status']]
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Attendance marked successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
