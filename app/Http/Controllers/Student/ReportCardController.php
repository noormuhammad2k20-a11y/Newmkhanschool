<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\ReportCard;
use App\Models\ExamType;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        $student     = auth()->user()->student;
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $examTypeId  = $request->input('exam_type_id');

        $examTypes = ExamType::all();

        $marks = [];
        $summary = null;

        if ($examTypeId) {
            $marks = Mark::with('subject')
                ->where('student_id', $student->id)
                ->where('academic_year_id', $academicYear?->id)
                ->where('exam_type_id', $examTypeId)
                ->get();

            $totalObtained = $marks->sum('marks_obtained');
            $totalMax      = $marks->sum('total_marks');
            $pct           = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1) : 0;

            $summary = [
                'obtained'   => $totalObtained,
                'total'      => $totalMax,
                'percentage' => $pct,
                'grade'      => $this->grade($pct),
                'rank'       => ReportCard::where('student_id', $student->id)
                                  ->where('exam_type_id', $examTypeId)
                                  ->value('rank'),
            ];
        }

        return view('student.report-card', compact('student','marks','summary','examTypes','examTypeId','academicYear'));
    }

    public function download(Request $request)
    {
        $student     = auth()->user()->student;
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $examTypeId  = $request->input('exam_type_id');

        $marks = Mark::with('subject')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYear?->id)
            ->where('exam_type_id', $examTypeId)
            ->get();

        $totalObtained = $marks->sum('marks_obtained');
        $totalMax      = $marks->sum('total_marks');
        $pct           = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1) : 0;

        $pdf = Pdf::loadView('student.report-card-pdf', compact('student','marks','academicYear','pct'));
        return $pdf->download("report_card_{$student->admission_no}.pdf");
    }

    private function grade(float $pct): string
    {
        return match(true) {
            $pct >= 90 => 'A+', $pct >= 80 => 'A',
            $pct >= 70 => 'B+', $pct >= 60 => 'B',
            $pct >= 50 => 'C',  $pct >= 40 => 'D',
            default    => 'F',
        };
    }
}
