<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AjaxResponseTrait;
use App\Models\TeacherLeaveRequest;
use App\Models\Teacher;
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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->ajaxError($request, 'Approval failed: '.$e->getMessage());
        }

        return $this->ajaxSuccess($request, 'Leave approved.');
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

    public function substituteSchedule()
    {
        return redirect()->route('admin.dashboard')->with('error', 'Substitute feature is currently disabled.');
    }

    public function leaveBalances()
    {
        return redirect()->route('admin.dashboard')->with('error', 'Leave Balances feature is currently disabled.');
    }
}
