<?php
namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Fee;
use App\Models\Announcement;
use App\Models\AcademicYear;

class DashboardController extends Controller
{
    private function getLinkedStudentIds(): \Illuminate\Support\Collection
    {
        return ParentStudent::where('parent_user_id', auth()->id())
            ->pluck('student_id');
    }

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
            $pending = Fee::where('student_id',$child->id)->whereIn('status',['Pending','Overdue'])->sum('amount');

            $childSummaries[$child->id] = [
                'attendance_pct' => $total > 0 ? round(($present/$total)*100,1) : 0,
                'pending_fees'   => $pending,
            ];
        }

        $announcements = Announcement::whereIn('target_role',['all','parent'])->latest()->take(5)->get();

        return view('parent.dashboard', compact('children','childSummaries','announcements'));
    }

    public function children()
    {
        $studentIds = $this->getLinkedStudentIds();
        $children   = Student::with(['currentClass','currentSection'])->whereIn('id', $studentIds)->get();
        return view('parent.children', compact('children'));
    }
}
