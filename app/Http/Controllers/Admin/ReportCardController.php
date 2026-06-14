<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AjaxResponseTrait;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Mark;
use App\Models\ReportCard;
use App\Models\AcademicYear;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportCardController extends Controller
{
    use AjaxResponseTrait;

    public function index()
    {
        $classes = SchoolClass::orderBy('name')->get();
        $activeYear = AcademicYear::where('is_active', 1)->first();
        
        // Get unique exam types
        $examTypes = \App\Models\ExamType::orderBy('name')->get();

        // Recent report cards
        $recentCards = ReportCard::with(['student:id,first_name,last_name,admission_no', 'academicYear:id,name', 'examType:id,name'])
            ->orderByDesc('created_at')
            ->take(50)
            ->get();

        return view('admin.report_cards.generate', compact('classes', 'activeYear', 'examTypes', 'recentCards'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'class_id'  => 'required|exists:classes,id',
            'exam_type_id' => 'required|exists:exam_types,id',
        ]);

        $activeYear = AcademicYear::where('is_active', 1)->first();
        abort_if(!$activeYear, 400, 'No active academic year.');

        $classId  = $request->class_id;
        $examTypeId = $request->exam_type_id;
        $examType = \App\Models\ExamType::find($examTypeId);

        $students = Student::where('current_class_id', $classId)
            ->where('status', 'Active')
            ->get();

        $generated = 0;

        // Calculate all student percentages first for ranking
        $studentPercentages = [];
        foreach ($students as $student) {
            $marks = Mark::where('student_id', $student->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('exam_type_id', $examTypeId)
                ->get();

            $totalObtained = $marks->sum('marks_obtained');
            $totalMax = $marks->sum('total_marks');
            $pct = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;

            $studentPercentages[$student->id] = $pct;
        }

        // Sort descending for rank
        arsort($studentPercentages);
        $ranks = [];
        $rank = 1;
        foreach ($studentPercentages as $sid => $pct) {
            $ranks[$sid] = $rank++;
        }

        // Determine grade from percentage
        $gradeMap = fn($p) => match(true) {
            $p >= 90 => 'A+',
            $p >= 80 => 'A',
            $p >= 70 => 'B',
            $p >= 60 => 'C',
            $p >= 50 => 'D',
            default  => 'F',
        };

        DB::beginTransaction();
        try {
            foreach ($students as $student) {
                $pct = $studentPercentages[$student->id] ?? 0;

                ReportCard::updateOrCreate(
                    [
                        'student_id'       => $student->id,
                        'academic_year_id' => $activeYear->id,
                        'exam_type_id'     => $examTypeId,
                    ],
                    [
                        'class_id'         => $classId,
                        'total_percentage' => $pct,
                        'grade'            => $gradeMap($pct),
                        'rank'             => $ranks[$student->id] ?? null,
                        'remarks'          => $pct >= 50 ? 'Promoted' : 'Needs Improvement',
                        'generated_by'     => auth()->id(),
                    ]
                );
                $generated++;
            }
            DB::commit();
            return $this->ajaxSuccess($request, "{$generated} report cards generated for {$examType->name}.", null, route('admin.reportcards.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->ajaxError($request, 'Generation failed: ' . $e->getMessage());
        }
    }

    public function downloadPdf($id)
    {
        $card = ReportCard::with(['student.currentClass', 'student.currentSection', 'academicYear', 'examType'])->findOrFail($id);
        $student = $card->student;
        $activeYear = $card->academicYear;
        $school = \App\Models\School::find(1);

        // Get all marks for this student for this exam type
        $marks = Mark::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('exam_type_id', $card->exam_type_id)
            ->with(['subject:id,name'])
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.report_card', compact('card', 'student', 'marks', 'school', 'activeYear'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('ReportCard-' . $student->admission_no . '-' . ($card->examType->name ?? 'Exam') . '.pdf');
    }
}
