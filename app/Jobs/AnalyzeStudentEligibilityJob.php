<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\AcademicCycleDetector;
use App\Services\StudentPromotionService;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Log;

class AnalyzeStudentEligibilityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $detector = new AcademicCycleDetector();
        
        $rules = \App\Models\AcademicCycleRule::where('is_active', true)->get();
        foreach ($rules as $rule) {
            if ($detector->isPromotionWindowActive($rule->school_id)) {
                Log::info("Promotion window active for school: " . $rule->school_id . ". Running auto-batch generation.");
                
                $classes = SchoolClass::where('school_id', $rule->school_id)->get();
                $promotionService = app(StudentPromotionService::class);
                
                // For simplicity, assuming current and next session logic
                $currentSessionId = \App\Models\AcademicYear::orderBy('id', 'desc')->skip(1)->take(1)->value('id') ?? 1; // dummy fallback
                $nextSessionId = \App\Models\AcademicYear::orderBy('id', 'desc')->take(1)->value('id') ?? 2;

                foreach ($classes as $class) {
                    $batchExists = \App\Models\PromotionBatch::where('school_id', $rule->school_id)
                        ->where('from_session_id', $currentSessionId)
                        ->where('from_class_id', $class->id)
                        ->exists();

                    if (!$batchExists) {
                        $nextClassId = \App\Models\SchoolClass::where('school_id', $rule->school_id)
                            ->where('id', '>', $class->id)
                            ->min('id');
                        
                        if ($nextClassId) {
                            try {
                                $promotionService->generateBatch([
                                    'from_academic_year_id' => $currentSessionId,
                                    'to_academic_year_id' => $nextSessionId,
                                    'from_class_id' => $class->id,
                                    'to_class_id' => $nextClassId,
                                ]);
                            } catch (\Exception $e) {
                                Log::error("Failed to generate batch for class: {$class->id}. Error: " . $e->getMessage());
                            }
                        }
                    }
                }
            }
        }
    }
}
