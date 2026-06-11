<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentLeaveRequest;
use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    use AjaxResponseTrait;
    public function index()
    {
        $student = auth()->user()->student;
        $leaves  = StudentLeaveRequest::where('student_id', $student->id)
                     ->latest()->paginate(15);
        return view('student.leave-requests', compact('leaves','student'));
    }

    public function store(Request $request)
    {
        $student = auth()->user()->student;

        $request->validate([
            'leave_type' => 'required|string|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:1000',
        ]);

        StudentLeaveRequest::create([
            'student_id' => $student->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => $request->reason,
            'status'     => 'Pending',
        ]);

        return $this->ajaxSuccess($request, 'Leave request submitted successfully.');
    }
}
