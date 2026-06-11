<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Fee;
use App\Models\StudentAttendance;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('admin.analytics.index');
    }

    public function branchAnalytics()
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $branches = \App\Models\School::withCount(['students' => function($q) {
            $q->where('status', 'Active');
        }, 'teachers'])->get();

        return view('admin.analytics.branch', compact('branches'));
    }

    public function branchRevenue()
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $branches = \App\Models\School::all();
        $revenueData = [];

        foreach ($branches as $branch) {
            $paid = Fee::where('school_id', $branch->id)->where('status', 'Paid')->sum('paid_amount');
            $pending = Fee::where('school_id', $branch->id)->where('status', 'Pending')->sum('amount');
            
            $revenueData[] = [
                'branch' => $branch->name,
                'paid' => $paid,
                'pending' => $pending
            ];
        }

        return view('admin.analytics.revenue', compact('branches', 'revenueData'));
    }

    // AJAX endpoint for all chart data
    public function chartData(Request $request)
    {
        $schoolId     = auth()->user()->hasRole('Super Admin') ? null : auth()->user()->school_id;
        $academicYear = AcademicYear::where('is_active', 1)->first();

        return response()->json([
            'fee_collection'    => $this->feeCollectionTrend($schoolId),
            'attendance_weekly' => $this->weeklyAttendance($schoolId, $academicYear),
            'class_performance' => $this->classPerformance($schoolId, $academicYear),
            'student_stats'     => $this->studentStats($schoolId),
            'fee_status_pie'    => $this->feeStatusPie($schoolId),
            'attendance_heatmap'=> $this->attendanceHeatmap($schoolId, $academicYear),
        ]);
    }

    private function feeCollectionTrend($schoolId): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date   = now()->subMonths($i);
            $q      = Fee::where('status','Paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);
            if ($schoolId) $q->where('school_id', $schoolId);
            $months[] = [
                'label'  => $date->format('M Y'),
                'amount' => (float) $q->sum('paid_amount'),
            ];
        }
        return $months;
    }

    private function weeklyAttendance($schoolId, $academicYear): array
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date  = now()->subDays($i)->toDateString();
            $q     = StudentAttendance::where('date', $date);
            if ($academicYear) $q->where('academic_year_id', $academicYear->id);
            $total   = $q->count();
            $present = (clone $q)->where('status','P')->count();
            $days[]  = [
                'date'    => $date,
                'label'   => now()->subDays($i)->format('D'),
                'present' => $present,
                'absent'  => $total - $present,
                'total'   => $total,
            ];
        }
        return $days;
    }

    private function classPerformance($schoolId, $academicYear): array
    {
        $classes = SchoolClass::orderBy('name')->get();
        return $classes->map(function ($class) use ($academicYear) {
            $studentIds = Student::where('current_class_id', $class->id)->pluck('id');
            $q          = Mark::whereIn('student_id', $studentIds);
            if ($academicYear) $q->where('academic_year_id', $academicYear->id);
            $total    = $q->sum('total_marks');
            $obtained = $q->sum('marks_obtained');
            return [
                'class'      => $class->name,
                'percentage' => $total > 0 ? round(($obtained/$total)*100,1) : 0,
            ];
        })->values()->toArray();
    }

    private function studentStats($schoolId): array
    {
        $q = Student::query();
        if ($schoolId) $q->where('school_id',$schoolId);
        return [
            'total'    => $q->count(),
            'active'   => (clone $q)->where('status','Active')->count(),
            'male'     => (clone $q)->where('gender','Male')->count(),
            'female'   => (clone $q)->where('gender','Female')->count(),
        ];
    }

    private function feeStatusPie($schoolId): array
    {
        $q = Fee::query();
        if ($schoolId) $q->where('school_id',$schoolId);
        return [
            ['label' => 'Paid',    'count' => (clone $q)->where('status','Paid')->count()],
            ['label' => 'Pending', 'count' => (clone $q)->where('status','Pending')->count()],
            ['label' => 'Overdue', 'count' => (clone $q)->where('status','Overdue')->count()],
        ];
    }

    private function attendanceHeatmap($schoolId, $academicYear): array
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date  = now()->subDays($i)->toDateString();
            $q     = StudentAttendance::where('date', $date);
            if ($academicYear) $q->where('academic_year_id', $academicYear->id);
            $total   = $q->count();
            $present = (clone $q)->where('status','P')->count();
            $data[]  = [
                'date'  => $date,
                'value' => $total > 0 ? round(($present/$total)*100) : 0,
            ];
        }
        return $data;
    }
}
