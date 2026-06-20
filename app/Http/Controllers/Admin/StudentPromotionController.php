<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AjaxResponseTrait;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\PromotionRule;
use App\Models\StudentPromotion;
use App\Services\StudentPromotionService;
use Illuminate\Http\Request;

class StudentPromotionController extends Controller
{
    use AjaxResponseTrait;

    protected StudentPromotionService $promotionService;

    public function __construct(StudentPromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    /**
     * Main promotions dashboard with dynamic stats.
     */
    public function index()
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $classes       = SchoolClass::orderBy('name')->get();
        $activeYear    = AcademicYear::where('is_active', 1)->first();
        $rules         = PromotionRule::with(['fromClass', 'toClass'])
                           ->where('academic_year_id', $activeYear?->id)->get();

        // Dynamic stats
        $stats = $this->promotionService->getDashboardStats($activeYear?->id);

        return view('admin.promotions.index', compact(
            'academicYears', 'classes', 'activeYear', 'rules', 'stats'
        ));
    }

    /**
     * AJAX: Load students for a given class/section.
     */
    public function loadStudents(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        $students = $this->promotionService->getEligibleStudents(
            (int) $request->class_id,
            $request->section_id ? (int) $request->section_id : null
        );

        return response()->json([
            'status'   => 'success',
            'total'    => $students->count(),
            'students' => $students->map(fn($s) => [
                'id'            => $s->id,
                'first_name'    => $s->first_name,
                'last_name'     => $s->last_name,
                'admission_no'  => $s->admission_no,
                'class_name'    => $s->currentClass?->name ?? 'N/A',
                'section_name'  => $s->currentSection?->name ?? 'N/A',
                'status'        => $s->status,
            ]),
        ]);
    }

    /**
     * AJAX: Get sections for a given class.
     */
    public function getSections(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $sections = $this->promotionService->getSectionsByClass((int) $request->class_id);

        return response()->json([
            'status'   => 'success',
            'sections' => $sections->map(fn($s) => [
                'id'   => $s->id,
                'name' => $s->name,
            ]),
        ]);
    }

    /**
     * Execute bulk promotion.
     */
    public function execute(Request $request)
    {
        $request->validate([
            'from_academic_year_id' => 'required|exists:academic_years,id',
            'to_academic_year_id'   => 'required|exists:academic_years,id',
            'from_class_id'         => 'required|exists:classes,id',
            'to_class_id'           => 'required|exists:classes,id',
            'to_section_id'         => 'nullable|exists:sections,id',
            'student_ids'           => 'required|array|min:1',
            'student_ids.*'         => 'exists:students,id',
        ]);

        // Pre-flight validation
        $validationErrors = $this->promotionService->validatePromotionRequest(
            $request->student_ids,
            (int) $request->from_class_id,
            (int) $request->to_class_id,
            $request->to_section_id ? (int) $request->to_section_id : null,
            (int) $request->from_academic_year_id,
            (int) $request->to_academic_year_id
        );

        if (!empty($validationErrors)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validationErrors,
            ], 422);
        }

        try {
            $summary = $this->promotionService->executeBulkPromotion([
                'student_ids'          => $request->student_ids,
                'from_academic_year_id'=> (int) $request->from_academic_year_id,
                'to_academic_year_id'  => (int) $request->to_academic_year_id,
                'from_class_id'        => (int) $request->from_class_id,
                'to_class_id'          => (int) $request->to_class_id,
                'to_section_id'        => $request->to_section_id ? (int) $request->to_section_id : null,
                'remarks'              => $request->remarks ?? 'Bulk promotion by admin',
            ]);

            $message = "{$summary['success']} student(s) promoted successfully.";
            if ($summary['skipped'] > 0) {
                $message .= " {$summary['skipped']} skipped (duplicates).";
            }
            if ($summary['failed'] > 0) {
                $message .= " {$summary['failed']} failed.";
            }

            return response()->json([
                'status'  => 'success',
                'message' => $message,
                'summary' => $summary,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Promotion failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Promotion history page.
     */
    public function history(Request $request)
    {
        $filters = $request->only([
            'academic_year_id', 'class_id', 'status',
            'search', 'batch_id', 'date_from', 'date_to',
        ]);

        $history       = $this->promotionService->getPromotionHistory($filters, 25);
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $classes       = SchoolClass::orderBy('name')->get();

        return view('admin.promotions.history', compact('history', 'academicYears', 'classes', 'filters'));
    }

    /**
     * Help page.
     */
    public function help()
    {
        return view('admin.promotions.help');
    }

    /**
     * CSV export of promotion history.
     */
    public function exportHistory(Request $request)
    {
        $filters = $request->only([
            'academic_year_id', 'class_id', 'status',
            'search', 'batch_id', 'date_from', 'date_to',
        ]);

        $records = $this->promotionService->getPromotionHistory($filters, 10000);

        $filename = 'promotion_history_' . now()->format('Y-m-d_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Student Name', 'Admission No',
                'From Session', 'To Session',
                'From Class', 'To Class',
                'From Section', 'To Section',
                'Status', 'Promoted By', 'Date', 'Remarks',
            ]);

            foreach ($records as $record) {
                fputcsv($file, [
                    ($record->student->first_name ?? '') . ' ' . ($record->student->last_name ?? ''),
                    $record->student->admission_no ?? '',
                    $record->academicYear->year ?? '',
                    $record->toAcademicYear->year ?? '',
                    $record->fromClass->name ?? '',
                    $record->toClass->name ?? '',
                    $record->fromSection->name ?? '',
                    $record->toSection->name ?? '',
                    ucfirst($record->status),
                    $record->promotedByUser->name ?? 'System',
                    $record->promoted_at?->format('Y-m-d H:i'),
                    $record->remarks ?? '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Manage promotion rules (passing criteria per class).
     */
    public function rules()
    {
        $classes      = SchoolClass::orderBy('name')->get();
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $rules        = PromotionRule::with(['fromClass', 'toClass'])
                          ->where('academic_year_id', $academicYear?->id)->get();
        return view('admin.promotions.rules', compact('classes', 'rules', 'academicYear'));
    }

    /**
     * Save a promotion rule.
     */
    public function saveRule(Request $request)
    {
        $request->validate([
            'from_class_id'      => 'required|exists:classes,id',
            'to_class_id'        => 'required|exists:classes,id',
            'min_percentage'     => 'required|numeric|min:0|max:100',
            'min_attendance_pct' => 'required|numeric|min:0|max:100',
            'academic_year_id'   => 'required|exists:academic_years,id',
        ]);

        PromotionRule::updateOrCreate(
            [
                'from_class_id'    => $request->from_class_id,
                'academic_year_id' => $request->academic_year_id,
            ],
            [
                'to_class_id'        => $request->to_class_id,
                'min_percentage'     => $request->min_percentage,
                'min_attendance_pct' => $request->min_attendance_pct,
            ]
        );

        return $this->ajaxSuccess($request, 'Promotion rule saved.');
    }
}
