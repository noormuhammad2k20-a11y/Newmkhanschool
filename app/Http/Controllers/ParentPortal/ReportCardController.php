<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;
use App\Models\Student;
use App\Models\Mark;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardController extends BaseParentController
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

    public function download($student_id)
    {
        abort_unless($this->parentOwnsStudent($student_id), 403);
        $student = Student::with(['currentClass','currentSection','marks.subject','marks.examSchedule'])->findOrFail($student_id);
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $pdf = Pdf::loadView('parent.report-card-pdf', compact('student', 'academicYear'));
        return $pdf->download("report-card-{$student->first_name}.pdf");
    }
}
