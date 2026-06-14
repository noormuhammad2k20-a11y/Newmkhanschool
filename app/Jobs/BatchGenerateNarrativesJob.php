<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BatchGenerateNarrativesJob implements ShouldQueue
{
    use Queueable;

    protected $academicYearId;
    protected $classId;

    public function __construct($academicYearId, $classId = null)
    {
        $this->academicYearId = $academicYearId;
        $this->classId = $classId;
    }

    public function handle(\App\Services\AiReportCardNarrativeService $narrativeService): void
    {
        $query = \App\Models\ReportCard::where('academic_year_id', $this->academicYearId);
        
        if ($this->classId) {
            $query->whereHas('student', function($q) {
                $q->where('class_id', $this->classId);
            });
        }

        $reportCards = $query->get();

        foreach ($reportCards as $rc) {
            try {
                $narrativeService->generateForReportCard($rc);
                // Sleep slightly to prevent hitting Gemini API rate limits immediately
                sleep(1); 
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to batch generate narrative for RC ID {$rc->id}: " . $e->getMessage());
            }
        }
    }
}
