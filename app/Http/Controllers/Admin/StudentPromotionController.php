<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Mark;
use App\Models\StudentAttendance;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\PromotionRule;
use App\Models\StudentPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPromotionController extends Controller
{
    // Step 1: Select academic year and class to promote from
    public function index()
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $classes       = SchoolClass::orderBy('name')->get();
        $activeYear    = AcademicYear::where('is_active', 1)->first();
        $rules         = PromotionRule::with(['fromClass','toClass'])
                           ->where('academic_year_id', $activeYear?->id)->get();
        return view('admin.promotions.index', compact('academicYears','classes','activeYear','rules'));
    }

    // Step 2: Preview which students pass / fail before committing
    public function preview(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id'         => 'required|exists:classes,id',
        ]);

        $academicYear = AcademicYear::findOrFail($request->academic_year_id);
        $class        = SchoolClass::findOrFail($request->class_id);
        $rule         = PromotionRule::where('from_class_id', $class->id)
                          ->where('academic_year_id', $academicYear->id)->first();

        $students = Student::with(['currentSection'])
            ->where('current_class_id', $class->id)
            ->where('status', 'Active')
            ->get();

        $results = $students->map(function ($student) use ($academicYear, $rule) {
            // Calculate total marks percentage
            $marks = Mark::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)->get();

            $totalObtained = $marks->sum('marks_obtained');
            $totalMax      = $marks->sum('total_marks');
            $marksPct      = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1) : 0;

            // Calculate attendance percentage
            $totalDays   = StudentAttendance::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)->count();
            $presentDays = StudentAttendance::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('status', 'P')->count();
            $attendPct   = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

            $minMarks  = $rule?->min_percentage ?? 40;
            $minAttend = $rule?->min_attendance_pct ?? 75;

            $passesMarks   = $marksPct >= $minMarks;
            $passesAttend  = $attendPct >= $minAttend;
            $isEligible    = $passesMarks && $passesAttend;

            return (object)[
                'student'         => $student,
                'marks_pct'       => $marksPct,
                'attendance_pct'  => $attendPct,
                'passes_marks'    => $passesMarks,
                'passes_attend'   => $passesAttend,
                'is_eligible'     => $isEligible,
                'to_class_id'     => $rule?->to_class_id,
            ];
        });

        $nextClasses = SchoolClass::orderBy('name')->get();

        return view('admin.promotions.preview', compact(
            'results', 'class', 'academicYear', 'rule', 'nextClasses'
        ));
    }

    // Step 3: Execute bulk promotion
    public function execute(Request $request)
    {
        $request->validate([
            'academic_year_id'   => 'required|exists:academic_years,id',
            'from_class_id'      => 'required|exists:classes,id',
            'to_class_id'        => 'required|exists:classes,id',
            'student_ids'        => 'required|array',
            'student_ids.*'      => 'exists:students,id',
            'default_section_id' => 'required|exists:sections,id',
        ]);

        DB::beginTransaction();
        try {
            $promoted = 0;
            foreach ($request->student_ids as $studentId) {
                $student = Student::findOrFail($studentId);

                StudentPromotion::create([
                    'student_id'       => $student->id,
                    'academic_year_id' => $request->academic_year_id,
                    'from_class_id'    => $student->current_class_id,
                    'from_section_id'  => $student->current_section_id,
                    'to_class_id'      => $request->to_class_id,
                    'to_section_id'    => $request->default_section_id,
                    'promotion_type'   => 'Promoted',
                    'promoted_by'      => auth()->id(),
                    'remarks'          => 'Bulk promotion by admin',
                ]);

                $student->update([
                    'current_class_id'   => $request->to_class_id,
                    'current_section_id' => $request->default_section_id,
                ]);

                $promoted++;
            }

            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', "{$promoted} students promoted successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Promotion failed: ' . $e->getMessage());
        }
    }

    // Manage promotion rules (passing criteria per class)
    public function rules()
    {
        $classes      = SchoolClass::orderBy('name')->get();
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $rules        = PromotionRule::with(['fromClass','toClass'])
                          ->where('academic_year_id', $academicYear?->id)->get();
        return view('admin.promotions.rules', compact('classes','rules','academicYear'));
    }

    public function saveRule(Request $request)
    {
        $request->validate([
            'from_class_id'        => 'required|exists:classes,id',
            'to_class_id'          => 'required|exists:classes,id',
            'min_percentage'       => 'required|numeric|min:0|max:100',
            'min_attendance_pct'   => 'required|numeric|min:0|max:100',
            'academic_year_id'     => 'required|exists:academic_years,id',
        ]);

        PromotionRule::updateOrCreate(
            [
                'from_class_id'    => $request->from_class_id,
                'academic_year_id' => $request->academic_year_id,
            ],
            [
                'to_class_id'          => $request->to_class_id,
                'min_percentage'       => $request->min_percentage,
                'min_attendance_pct'   => $request->min_attendance_pct,
            ]
        );

        return back()->with('success', 'Promotion rule saved.');
    }
}
