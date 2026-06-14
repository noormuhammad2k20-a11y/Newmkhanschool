<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AutoBadgeEvaluationJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        // Simple example of auto-awarding a "Perfect Attendance" badge for the month.
        // In a real scenario, this would query attendance records.
        $students = \App\Models\Student::where('status', 'active')->get();

        foreach ($students as $student) {
            // Mock condition: if attendance > 95%
            $hasExcellentAttendance = true; // Placeholder for real logic

            if ($hasExcellentAttendance) {
                \App\Models\StudentBadge::firstOrCreate([
                    'student_id' => $student->id,
                    'badge_type' => 'Attendance',
                    'title' => 'Perfect Attendance - ' . date('F Y')
                ], [
                    'description' => 'Awarded for maintaining over 95% attendance this month.',
                    'icon' => 'calendar_month',
                    'awarded_by' => 1, // System admin user ID
                    'awarded_at' => now()
                ]);
            }
        }
    }
}
