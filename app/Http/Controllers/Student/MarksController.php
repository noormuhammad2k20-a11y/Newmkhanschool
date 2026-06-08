<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\ExamType;
use App\Models\AcademicYear;

class MarksController extends Controller
{
    public function index()
    {
        $student     = auth()->user()->student;
        $academicYear = AcademicYear::where('is_active', 1)->first();

        $marks = Mark::with(['subject','examType'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYear?->id)
            ->get()
            ->groupBy('exam_type_id');

        $examTypes = ExamType::all()->keyBy('id');

        // Calculate totals per exam type
        $summaries = [];
        foreach ($marks as $examTypeId => $examMarks) {
            $obtained = $examMarks->sum('marks_obtained');
            $total    = $examMarks->sum('total_marks');
            $pct      = $total > 0 ? round(($obtained / $total) * 100, 1) : 0;
            $summaries[$examTypeId] = [
                'obtained'   => $obtained,
                'total'      => $total,
                'percentage' => $pct,
                'grade'      => $this->calculateGrade($pct),
            ];
        }

        return view('student.marks', compact('marks','examTypes','summaries','student'));
    }

    private function calculateGrade(float $pct): string
    {
        return match(true) {
            $pct >= 90 => 'A+',
            $pct >= 80 => 'A',
            $pct >= 70 => 'B+',
            $pct >= 60 => 'B',
            $pct >= 50 => 'C',
            $pct >= 40 => 'D',
            default    => 'F',
        };
    }
}
