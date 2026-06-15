<?php
namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Fee;
use App\Models\Announcement;
use App\Models\AcademicYear;

class DashboardController extends BaseParentController
{

    public function index()
    {
        $studentIds = $this->getLinkedStudentIds();
        abort_if($studentIds->isEmpty(), 403, 'No children linked to your account. Please contact the school.');

        $children    = Student::with(['currentClass','currentSection'])->whereIn('id', $studentIds)->get();
        $academicYear = AcademicYear::where('is_active', 1)->first();

        // Summary per child
        $childSummaries = [];
        foreach ($children as $child) {
            $total   = StudentAttendance::where('student_id',$child->id)->where('academic_year_id',$academicYear?->id)->count();
            $present = StudentAttendance::where('student_id',$child->id)->where('academic_year_id',$academicYear?->id)->where('status','P')->count();
            $pending = Fee::where('student_id',$child->id)
                ->whereIn('status',['Pending','Overdue','Partial'])
                ->get()
                ->sum(function($fee) {
                    return $fee->amount - $fee->paid_amount - $fee->discount + $fee->fine;
                });

            $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
            $attendances = StudentAttendance::where('student_id', $child->id)
                ->where('date', '>=', $sixMonthsAgo)
                ->get();
                
            $monthlyChart = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $label = $month->format('M Y');
                
                $monthAttendances = $attendances->filter(function ($att) use ($month) {
                    return \Carbon\Carbon::parse($att->date)->format('Y-m') === $month->format('Y-m');
                });
                
                $monthlyChart[] = [
                    'label' => $label,
                    'present' => $monthAttendances->where('status', 'P')->count(),
                    'absent' => $monthAttendances->where('status', 'A')->count(),
                ];
            }

            $childSummaries[$child->id] = [
                'attendance_pct' => $total > 0 ? round(($present/$total)*100,1) : 0,
                'pending_fees'   => $pending,
                'monthly_chart'  => $monthlyChart,
            ];
        }



        return view('parent.dashboard', compact('children','childSummaries'));
    }

    public function children()
    {
        $studentIds = $this->getLinkedStudentIds();
        $children   = Student::with(['currentClass','currentSection'])->whereIn('id', $studentIds)->get();
        return view('parent.children', compact('children'));
    }
}
