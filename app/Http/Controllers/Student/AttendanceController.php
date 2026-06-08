<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentLeaveRequest;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student     = auth()->user()->student;
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $month       = $request->input('month', now()->month);
        $year        = $request->input('year', now()->year);

        // Get all attendance records for the selected month
        $records = StudentAttendance::where('student_id', $student->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(fn($r) => Carbon::parse($r->date)->format('Y-m-d'));

        // Full-year stats
        $stats = [
            'present' => StudentAttendance::where('student_id', $student->id)
                           ->where('academic_year_id', $academicYear?->id)->where('status','P')->count(),
            'absent'  => StudentAttendance::where('student_id', $student->id)
                           ->where('academic_year_id', $academicYear?->id)->where('status','A')->count(),
            'leave'   => StudentAttendance::where('student_id', $student->id)
                           ->where('academic_year_id', $academicYear?->id)->where('status','L')->count(),
        ];
        $stats['total']      = $stats['present'] + $stats['absent'] + $stats['leave'];
        $stats['percentage'] = $stats['total'] > 0
            ? round(($stats['present'] / $stats['total']) * 100, 1) : 0;

        // Leave requests
        $leaveRequests = StudentLeaveRequest::where('student_id', $student->id)
                           ->latest()->take(10)->get();

        // Build calendar days for the month
        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $daysInMonth  = $startOfMonth->daysInMonth;
        $startDay     = $startOfMonth->dayOfWeek; // 0=Sunday

        return view('student.attendance', compact(
            'records','stats','leaveRequests','month','year','daysInMonth','startDay'
        ));
    }
}
