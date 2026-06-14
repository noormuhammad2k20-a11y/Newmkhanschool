<?php

namespace App\Services;

class AiTimetableOptimizerService
{
    protected $aiService;

    public function __construct(GeminiAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function optimize(array $teachers, array $classes, array $constraints)
    {
        // Convert to JSON strings for prompt
        $teachersStr = json_encode($teachers);
        $classesStr = json_encode($classes);
        $constraintsStr = json_encode($constraints);

        $prompt = "You are an AI Timetable Optimizer. Resolve scheduling conflicts and allocate resources efficiently.
Teachers: {$teachersStr}
Classes: {$classesStr}
Constraints: {$constraintsStr}

Generate a JSON object representing the optimized weekly schedule. Ensure no teacher is double-booked and all constraints are met.
Format:
{
  \"schedule\": [
     {\"day\": \"Monday\", \"time\": \"08:00 AM\", \"class_id\": 1, \"teacher_id\": 2, \"subject\": \"Math\"}
  ],
  \"unresolved_conflicts\": []
}";

        return $this->aiService->generateJson($prompt);
    }
}
