<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function show($student_id)
    {
        ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
            
        $student = Student::with('currentClass')->findOrFail($student_id);
        
        $leaves = DB::table('student_leave_requests')
            ->where('student_id', $student_id)
            ->orderBy('created_at', 'desc')->get();
            
        return view('parent.child-leave', compact('student', 'leaves'));
    }

    public function store(Request $request, $student_id)
    {
        ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
            
        $validated = $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:500',
        ]);
        
        DB::table('student_leave_requests')->insert([
            'student_id' => $student_id,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'],
            'reason'     => $validated['reason'],
            'status'     => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return back()->with('success', 'Leave application submitted.');
    }
}
