<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AiStudentRiskAnalysisJob implements ShouldQueue
{
    use Queueable;

    public function handle(\App\Services\GeminiAiService $aiService): void
    {
        // For demonstration, we'll process 10 active students at a time to avoid rate limits
        $students = \App\Models\Student::where('status', 'active')->limit(10)->get();

        foreach ($students as $student) {
            // In reality, gather actual attendance % and average grades here
            $mockAttendance = 85; 
            $mockGrade = 'C-';

            $prompt = "You are a Student Risk Analysis AI. Analyze this student for 'dropout', 'failing', or 'attendance' risks.
Student: {$student->first_name} {$student->last_name}
Attendance: {$mockAttendance}%
Average Grade: {$mockGrade}

Return strictly JSON format:
{
  \"risk_type\": \"attendance\",
  \"probability\": 0.75,
  \"factors\": [\"Attendance is below 90%\", \"Grades are slipping\"],
  \"recommendations\": \"Schedule a parent-teacher meeting regarding attendance.\"
}";
            
            $jsonResult = $aiService->generateJson($prompt);

            if ($jsonResult && isset($jsonResult['risk_type'])) {
                \App\Models\AiPrediction::create([
                    'student_id' => $student->id,
                    'risk_type' => $jsonResult['risk_type'],
                    'probability' => $jsonResult['probability'] ?? 0.50,
                    'factors' => $jsonResult['factors'] ?? [],
                    'recommendations' => $jsonResult['recommendations'] ?? '',
                    'predicted_at' => now()
                ]);
            }
            
            sleep(1); // rate limiting
        }
    }
}
