<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentPromotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentPromotionService
{
    /**
     * Get students filtered by class and optionally section.
     * Eager-loads relationships to avoid N+1 queries.
     */
    public function getEligibleStudents(int $classId, ?int $sectionId = null): \Illuminate\Database\Eloquent\Collection
    {
        return Student::with(['currentClass', 'currentSection'])
            ->where('current_class_id', $classId)
            ->when($sectionId, fn($q) => $q->where('current_section_id', $sectionId))
            ->whereIn('status', ['Active', 'Regular'])
            ->orderBy('first_name')
            ->get();
    }

    /**
     * Get sections belonging to a specific class.
     */
    public function getSectionsByClass(int $classId): \Illuminate\Database\Eloquent\Collection
    {
        return Section::where('class_id', $classId)->orderBy('name')->get();
    }

    /**
     * Compute real-time dashboard statistics.
     */
    public function getDashboardStats(?int $academicYearId = null): array
    {
        $totalStudents = Student::whereIn('status', ['Active', 'Regular'])->count();

        $baseQuery = StudentPromotion::query();
        if ($academicYearId) {
            $baseQuery->where('academic_year_id', $academicYearId);
        }

        $promoted   = (clone $baseQuery)->where('status', 'success')->count();
        $failed     = (clone $baseQuery)->where('status', 'failed')->count();
        $total      = (clone $baseQuery)->count();
        $rate       = $total > 0 ? round(($promoted / $total) * 100, 1) : 0;

        return [
            'total_students'  => $totalStudents,
            'promoted'        => $promoted,
            'failed'          => $failed,
            'completion_rate' => $rate,
        ];
    }

    /**
     * Validate promotion request before processing.
     * Returns array of error messages (empty = valid).
     */
    public function validatePromotionRequest(
        array $studentIds,
        int   $fromClassId,
        int   $toClassId,
        ?int  $toSectionId,
        int   $fromAcademicYearId,
        int   $toAcademicYearId
    ): array {
        $errors = [];

        // Prevent same-class + same-session promotion
        if ($fromClassId === $toClassId && $fromAcademicYearId === $toAcademicYearId) {
            $errors[] = 'Cannot promote students to the same class and session.';
        }

        // Verify destination class exists
        if (!SchoolClass::find($toClassId)) {
            $errors[] = 'Destination class does not exist.';
        }

        // Verify destination section exists (if provided)
        if ($toSectionId && !Section::find($toSectionId)) {
            $errors[] = 'Destination section does not exist.';
        }

        // Verify destination academic year exists
        if (!AcademicYear::find($toAcademicYearId)) {
            $errors[] = 'Destination academic session does not exist.';
        }

        // Check for already-promoted students
        $alreadyPromoted = StudentPromotion::whereIn('student_id', $studentIds)
            ->where('to_academic_year_id', $toAcademicYearId)
            ->where('to_class_id', $toClassId)
            ->where('status', 'success')
            ->pluck('student_id')
            ->toArray();

        if (!empty($alreadyPromoted)) {
            $students = Student::whereIn('id', $alreadyPromoted)->get();
            $names = $students->map(fn($s) => $s->first_name . ' ' . $s->last_name)->implode(', ');
            $errors[] = "The following students have already been promoted to this destination: {$names}";
        }

        return $errors;
    }

    /**
     * Execute bulk promotion with per-student error handling.
     * Returns a detailed summary.
     */
    public function executeBulkPromotion(array $data): array
    {
        $batchId = Str::uuid()->toString();
        $summary = [
            'batch_id'  => $batchId,
            'total'     => count($data['student_ids']),
            'success'   => 0,
            'failed'    => 0,
            'skipped'   => 0,
            'errors'    => [],
            'promoted'  => [],
        ];

        $schoolId = auth()->user()->school_id ?? null;

        DB::beginTransaction();
        try {
            foreach ($data['student_ids'] as $studentId) {
                try {
                    $student = Student::findOrFail($studentId);

                    // Skip if already promoted to same destination
                    $exists = StudentPromotion::where('student_id', $studentId)
                        ->where('to_academic_year_id', $data['to_academic_year_id'])
                        ->where('to_class_id', $data['to_class_id'])
                        ->where('status', 'success')
                        ->exists();

                    if ($exists) {
                        StudentPromotion::create([
                            'student_id'          => $studentId,
                            'academic_year_id'    => $data['from_academic_year_id'],
                            'to_academic_year_id' => $data['to_academic_year_id'],
                            'from_class_id'       => $student->current_class_id,
                            'from_section_id'     => $student->current_section_id,
                            'to_class_id'         => $data['to_class_id'],
                            'to_section_id'       => $data['to_section_id'] ?? null,
                            'promotion_type'      => 'Promoted',
                            'status'              => 'skipped',
                            'promoted_by'         => auth()->id(),
                            'remarks'             => 'Skipped — already promoted to this destination',
                            'error_message'       => 'Duplicate promotion prevented',
                            'batch_id'            => $batchId,
                            'school_id'           => $schoolId,
                        ]);

                        $summary['skipped']++;
                        $summary['errors'][] = [
                            'student_id'   => $studentId,
                            'student_name' => $student->first_name . ' ' . $student->last_name,
                            'reason'       => 'Already promoted to this destination',
                        ];
                        continue;
                    }

                    // Record the promotion
                    StudentPromotion::create([
                        'student_id'          => $studentId,
                        'academic_year_id'    => $data['from_academic_year_id'],
                        'to_academic_year_id' => $data['to_academic_year_id'],
                        'from_class_id'       => $student->current_class_id,
                        'from_section_id'     => $student->current_section_id,
                        'to_class_id'         => $data['to_class_id'],
                        'to_section_id'       => $data['to_section_id'] ?? null,
                        'promotion_type'      => 'Promoted',
                        'status'              => 'success',
                        'promoted_by'         => auth()->id(),
                        'remarks'             => $data['remarks'] ?? 'Bulk promotion by admin',
                        'batch_id'            => $batchId,
                        'school_id'           => $schoolId,
                    ]);

                    // Update student's current class/section
                    $student->update([
                        'current_class_id'   => $data['to_class_id'],
                        'current_section_id' => $data['to_section_id'] ?? $student->current_section_id,
                    ]);

                    $summary['success']++;
                    $summary['promoted'][] = [
                        'student_id'   => $studentId,
                        'student_name' => $student->first_name . ' ' . $student->last_name,
                    ];

                } catch (\Exception $e) {
                    // Per-student failure — log it but continue batch
                    StudentPromotion::create([
                        'student_id'          => $studentId,
                        'academic_year_id'    => $data['from_academic_year_id'],
                        'to_academic_year_id' => $data['to_academic_year_id'],
                        'from_class_id'       => $data['from_class_id'],
                        'from_section_id'     => null,
                        'to_class_id'         => $data['to_class_id'],
                        'to_section_id'       => $data['to_section_id'] ?? null,
                        'promotion_type'      => 'Promoted',
                        'status'              => 'failed',
                        'promoted_by'         => auth()->id(),
                        'error_message'       => $e->getMessage(),
                        'batch_id'            => $batchId,
                        'school_id'           => $schoolId,
                    ]);

                    $summary['failed']++;
                    $summary['errors'][] = [
                        'student_id'   => $studentId,
                        'student_name' => 'Unknown',
                        'reason'       => $e->getMessage(),
                    ];
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $summary;
    }

    /**
     * Get paginated promotion history with filters.
     */
    public function getPromotionHistory(array $filters = [], int $perPage = 20)
    {
        $query = StudentPromotion::with([
            'student',
            'academicYear',
            'toAcademicYear',
            'fromClass',
            'toClass',
            'fromSection',
            'toSection',
            'promotedByUser',
        ])->orderByDesc('promoted_at');

        if (!empty($filters['academic_year_id'])) {
            $query->where('academic_year_id', $filters['academic_year_id']);
        }

        if (!empty($filters['class_id'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('from_class_id', $filters['class_id'])
                  ->orWhere('to_class_id', $filters['class_id']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['batch_id'])) {
            $query->where('batch_id', $filters['batch_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('admission_no', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['date_from'])) {
            $query->where('promoted_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('promoted_at', '<=', $filters['date_to'] . ' 23:59:59');
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
