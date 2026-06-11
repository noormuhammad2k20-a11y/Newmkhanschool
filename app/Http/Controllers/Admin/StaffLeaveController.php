<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AjaxResponseTrait;
use App\Models\TeacherLeaveRequest;
use App\Models\SubstituteAssignment;
use App\Models\Teacher;
use App\Models\TeacherLeaveBalance;
use App\Models\AcademicYear;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffLeaveController extends Controller
{
    use AjaxResponseTrait;
    public function index()
    {
        $leaves = TeacherLeaveRequest::with(['teacher.user'])
            ->orderByDesc('created_at')->paginate(20);
        $pendingCount = TeacherLeaveRequest::where('status','Pending')->count();
        return view('admin.staff-leaves.index', compact('leaves','pendingCount'));
    }

    public function approve(Request $request, $id)
    {
        $leave   = TeacherLeaveRequest::with('teacher')->findOrFail($id);
        $request->validate([
            'substitute_teacher_id' => 'nullable|exists:teachers,id',
        ]);

        DB::beginTransaction();
        try {
            $leave->update([
                'status'      => 'Approved',
                'approved_by' => auth()->id(),
            ]);

            // Update leave balance
            $academicYear = AcademicYear::where('is_active',1)->first();
            $balance = TeacherLeaveBalance::firstOrCreate(
                ['teacher_id' => $leave->teacher_id, 'academic_year_id' => $academicYear->id],
                ['casual_total'=>12,'casual_used'=>0,'sick_total'=>10,'sick_used'=>0,'annual_total'=>15,'annual_used'=>0]
            );

            $leaveType = strtolower($leave->leave_type);
            if (str_contains($leaveType,'sick'))   $balance->increment('sick_used', $leave->total_days);
            elseif (str_contains($leaveType,'annual')) $balance->increment('annual_used', $leave->total_days);
            else $balance->increment('casual_used', $leave->total_days);

            // Auto-assign substitute if provided
            if ($request->substitute_teacher_id) {
                $this->assignSubstitute($leave, $request->substitute_teacher_id);
                $leave->update(['substitute_assigned' => 1]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->ajaxError($request, 'Approval failed: '.$e->getMessage());
        }

        return $this->ajaxSuccess($request, 'Leave approved.' . ($request->substitute_teacher_id ? ' Substitute assigned.' : ''));
    }

    public function reject(Request $request, $id)
    {
        $leave = TeacherLeaveRequest::findOrFail($id);
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        $leave->update([
            'status'           => 'Rejected',
            'approved_by'      => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        return $this->ajaxSuccess($request, 'Leave request rejected.');
    }

    public function assignSubstituteManually(Request $request, $leaveId)
    {
        $leave = TeacherLeaveRequest::findOrFail($leaveId);
        $request->validate(['substitute_teacher_id' => 'required|exists:teachers,id']);

        $this->assignSubstitute($leave, $request->substitute_teacher_id);
        $leave->update(['substitute_assigned' => 1]);

        return $this->ajaxSuccess($request, 'Substitute teacher assigned.');
    }

    public function substituteSchedule()
    {
        $substitutes = SubstituteAssignment::with([
            'originalTeacher.user',
            'substituteTeacher.user',
            'class_',
            'subject',
        ])->where('date','>=', today()->toDateString())
          ->orderBy('date')->paginate(20);
        return view('admin.staff-leaves.substitutes', compact('substitutes'));
    }

    public function leaveBalances()
    {
        $academicYear = AcademicYear::where('is_active',1)->first();
        $teachers     = Teacher::with(['user','leaveBalance' => function($q) use ($academicYear) {
            $q->where('academic_year_id', $academicYear?->id);
        }])->get();
        return view('admin.staff-leaves.balances', compact('teachers','academicYear'));
    }

    // Auto-assign substitute based on original teacher's timetable
    private function assignSubstitute(TeacherLeaveRequest $leave, int $substituteTeacherId): void
    {
        // Get the original teacher's timetable for the leave period
        $startDate = \Carbon\Carbon::parse($leave->start_date);
        $endDate   = \Carbon\Carbon::parse($leave->end_date);

        $timetableEntries = Timetable::where('teacher_id', $leave->teacher_id)->get();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayName = $date->format('l'); // Monday, Tuesday...
            $dayEntries = $timetableEntries->where('day_of_week', $dayName);

            foreach ($dayEntries as $entry) {
                SubstituteAssignment::firstOrCreate(
                    [
                        'leave_request_id'       => $leave->id,
                        'original_teacher_id'    => $leave->teacher_id,
                        'substitute_teacher_id'  => $substituteTeacherId,
                        'class_id'               => $entry->class_id,
                        'subject_id'             => $entry->subject_id_ref ?? $entry->subject_id,
                        'date'                   => $date->toDateString(),
                    ],
                    [
                        'period_time' => $entry->start_time . ' - ' . $entry->end_time,
                        'status'     => 'Assigned',
                        'assigned_by'=> auth()->id(),
                    ]
                );
            }
        }
    }
}
