<?php

namespace App\Services;

use App\Models\ReportCard;
use App\Models\ReportCardNarrative;
use App\Models\Student;
use App\Models\Mark;
use App\Models\StudentAttendance;
use Carbon\Carbon;

class AiReportCardNarrativeService
{
    protected $geminiService;

    public function __construct(GeminiAiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Generate or update the AI narrative for a specific report card using Gemini API.
     */
    public function generateForReportCard(ReportCard $reportCard)
    {
        $student = $reportCard->student;
        $academicYearId = $reportCard->academic_year_id;

        // Fetch existing narrative to check lock status
        $existing = ReportCardNarrative::where('report_card_id', $reportCard->id)->first();
        if ($existing && $existing->is_locked) {
            return $existing; // Cannot regenerate locked narrative
        }

        // Build data for Gemini
        $attendanceData = $this->getAttendanceData($student->id, $academicYearId);
        $performanceData = $this->getPerformanceData($reportCard);
        
        $prompt = $this->buildPrompt($student, $attendanceData, $performanceData);
        
        $jsonResult = $this->geminiService->generateJson($prompt);

        if (!$jsonResult) {
            throw new \Exception("Failed to generate AI narrative from Gemini.");
        }

        $history = $existing ? $existing->narrative_history ?? [] : [];
        if ($existing) {
            $history[] = [
                'version' => $existing->version,
                'strengths' => $existing->strengths,
                'improvements' => $existing->improvements,
                'generated_at' => $existing->generated_at
            ];
        }

        $narrative = ReportCardNarrative::updateOrCreate(
            ['report_card_id' => $reportCard->id],
            [
                'strengths' => $jsonResult['strengths'] ?? 'Data pending.',
                'improvements' => $jsonResult['improvements'] ?? 'Data pending.',
                'attendance_summary' => $jsonResult['attendance_summary'] ?? 'Data pending.',
                'teacher_comments' => $jsonResult['teacher_comments'] ?? '',
                'parent_guidance' => $jsonResult['parent_guidance'] ?? '',
                'next_term_goals' => $jsonResult['next_term_goals'] ?? '',
                'generated_by_ai' => true,
                'generated_at' => Carbon::now(),
                'version' => $existing ? $existing->version + 1 : 1,
                'ai_confidence_score' => $jsonResult['confidence_score'] ?? 0.85,
                'narrative_history' => $history
            ]
        );

        return $narrative;
    }

    private function getAttendanceData($studentId, $academicYearId)
    {
        // Mock data for now to avoid breaking without actual models
        return [
            'percentage' => 92,
            'late_days' => 2,
            'absent_days' => 4
        ];
    }

    private function getPerformanceData(ReportCard $reportCard)
    {
        // Gather marks data
        return [
            'overall_percentage' => $reportCard->percentage,
            'gpa' => $reportCard->gpa,
            'grade' => $reportCard->grade,
            'strong_subjects' => ['Mathematics', 'Science'],
            'weak_subjects' => ['History']
        ];
    }

    private function buildPrompt($student, $attendance, $performance)
    {
        return "Act as a Senior Academic Advisor. Generate a structured report card narrative for a student.
Student Name: {$student->first_name} {$student->last_name}
Overall Performance: {$performance['overall_percentage']}% (Grade {$performance['grade']})
Strong Subjects: " . implode(', ', $performance['strong_subjects']) . "
Weak Subjects: " . implode(', ', $performance['weak_subjects']) . "
Attendance: {$attendance['percentage']}%

Generate a JSON response strictly with these exact keys:
{
  \"strengths\": \"detailed paragraph on what they did well\",
  \"improvements\": \"paragraph on areas to improve\",
  \"attendance_summary\": \"paragraph explaining attendance impact\",
  \"teacher_comments\": \"motivational sign-off comment\",
  \"parent_guidance\": \"actionable advice for parents\",
  \"next_term_goals\": \"2-3 specific goals\",
  \"confidence_score\": 0.95
}";
    }
}
