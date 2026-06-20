<?php

namespace App\Services;

use App\Models\Student;

class PromotionEligibilityAnalyzer
{
    /**
     * Analyzes a student's eligibility for promotion.
     * Returns an array with score, category, and risk flags.
     */
    public function analyze(Student $student, int $academicYearId): array
    {
        $flags = [];
        $score = 100;

        // 1. Attendance Check
        $attendancePercentage = $this->calculateAttendance($student, $academicYearId);
        if ($attendancePercentage < 60) {
            $flags[] = "critical_low_attendance ({$attendancePercentage}%)";
            $score -= 40;
        } elseif ($attendancePercentage < 75) {
            $flags[] = "low_attendance ({$attendancePercentage}%)";
            $score -= 20;
        }

        // 2. Fee Clearance Check
        $feePending = $this->hasPendingFees($student, $academicYearId);
        if ($feePending) {
            $flags[] = "fee_pending";
            $score -= 30;
        }

        // 3. Exam Results Check
        $failedExams = $this->getFailedExamsCount($student, $academicYearId);
        if ($failedExams > 2) {
            $flags[] = "multiple_failed_exams ({$failedExams})";
            $score -= 50;
        } elseif ($failedExams > 0) {
            $flags[] = "failed_exams ({$failedExams})";
            $score -= 20;
        }

        // Determine category
        $category = 'eligible';
        if ($score < 50 || count($flags) >= 2 || in_array("multiple_failed_exams ({$failedExams})", $flags)) {
            $category = 'defaulter';
        } elseif ($score < 80 || !empty($flags)) {
            $category = 'conditional';
        }

        return [
            'eligibility_score' => max(0, $score),
            'category' => $category,
            'risk_flags' => $flags,
        ];
    }

    protected function calculateAttendance(Student $student, int $academicYearId): int
    {
        // In a real scenario, query the Attendance model.
        // For demonstration of the AI Engine, we simulate this based on student ID to keep it deterministic per student
        srand($student->id * 10);
        return rand(50, 100); 
    }

    protected function hasPendingFees(Student $student, int $academicYearId): bool
    {
        // Simulation
        srand($student->id * 20);
        return rand(1, 100) > 85; // 15% chance
    }

    protected function getFailedExamsCount(Student $student, int $academicYearId): int
    {
        // Simulation
        srand($student->id * 30);
        $rand = rand(1, 100);
        if ($rand > 95) return 3;
        if ($rand > 85) return 1;
        return 0;
    }
}
