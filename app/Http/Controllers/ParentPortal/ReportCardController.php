<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;
use App\Models\Student;
use App\Models\Mark;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportCardController extends Controller
{
    public function show($student_id)
    {
        ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)->firstOrFail();
            
        $student = Student::with(['currentClass'])->findOrFail($student_id);
        $marks = DB::table('marks')
            ->where('student_id', $student_id)
            ->join('subjects', 'marks.subject_id', '=', 'subjects.id')
            ->select('marks.*', 'subjects.name as subject_name')
            ->get();
            
        // Assuming report_cards table
        $reportCard = DB::table('report_cards')->where('student_id', $student_id)->latest('created_at')->first();
        
        return view('parent.child-report-card', compact('student', 'marks', 'reportCard'));
    }
}
