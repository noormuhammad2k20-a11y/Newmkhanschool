<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fee;

class DefaulterController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        
        $defaulters = Fee::with(['student.currentClass', 'student.currentSection', 'category'])
            ->whereHas('student', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->where('status', '!=', 'Paid')
            ->where('due_date', '<', now())
            ->latest('due_date')
            ->paginate(15);
            
        return view('accountant.fees.defaulters', compact('defaulters'));
    }

    public function remind(Request $request, $studentId)
    {
        // Mock sending reminders for a specific student
        return back()->with('success', 'Reminder sent successfully to student.');
    }
    
    public function sendReminders(Request $request)
    {
        // Mock sending reminders to all
        return back()->with('success', 'Reminders sent successfully to all selected defaulters.');
    }
}
