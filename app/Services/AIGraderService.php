<?php

namespace App\Services;

use App\Models\AssignmentSubmission;
use App\Models\AIGradingResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIGraderService
{
    /**
     * Grade an assignment submission using OpenAI.
     *
     * @param int $submissionId
     * @return array
     */
    public function gradeSubmission($submissionId)
    {
        $submission = AssignmentSubmission::with(['assignment', 'student'])->findOrFail($submissionId);
        $assignment = $submission->assignment;

        // If no file path or notes exist, there is nothing to grade.
        if (empty($submission->file_path) && empty($submission->notes)) {
            return ['status' => 'error', 'message' => 'Submission is empty.'];
        }

        $studentAnswer = $submission->notes ?? 'No written notes provided.';

        $apiKey = env('GEMINI_API_KEY') ?? env('OPENAI_API_KEY');
        if (empty($apiKey)) {
            return ['status' => 'error', 'message' => 'GEMINI_API_KEY is not configured.'];
        }

        $prompt = "You are an expert AI teacher grading a student's submission.\n";
        $prompt .= "Assignment Title: " . $assignment->title . "\n";
        $prompt .= "Assignment Description: " . $assignment->description . "\n";
        $prompt .= "Student Answer/Notes: " . $studentAnswer . "\n\n";
        $prompt .= "Please provide your grading in JSON format with the following keys:\n";
        $prompt .= "- 'suggested_score': A score from 0 to 100 based on the quality of the answer.\n";
        $prompt .= "- 'feedback': A concise, constructive feedback paragraph for the student.\n";
        $prompt .= "- 'rubric_breakdown': An object breaking down the score into 'Clarity', 'Accuracy', and 'Completeness' out of 10 each.\n";

        $parts = [
            ['text' => $prompt]
        ];

        // Attach the uploaded file if it exists and is a supported type
        if (!empty($submission->file_path)) {
            $fullFilePath = storage_path('app/public/' . ltrim($submission->file_path, '/'));
            if (file_exists($fullFilePath)) {
                $extension = strtolower(pathinfo($fullFilePath, PATHINFO_EXTENSION));
                $mimeMap = [
                    'pdf' => 'application/pdf',
                    'png' => 'image/png',
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                ];
                
                if (isset($mimeMap[$extension])) {
                    $fileData = base64_encode(file_get_contents($fullFilePath));
                    $parts[] = [
                        'inlineData' => [
                            'mimeType' => $mimeMap[$extension],
                            'data' => $fileData
                        ]
                    ];
                } else {
                    $parts[0]['text'] .= "\n[The student attached a {$extension} file, but the AI grader cannot directly process this format.]";
                }
            } else {
                $parts[0]['text'] .= "\n[The attached file could not be found on the server.]";
            }
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'system_instruction' => [
                    'parts' => [
                        ['text' => 'You are a strict but fair teacher who outputs only valid JSON.']
                    ]
                ],
                'contents' => [
                    [
                        'parts' => $parts
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract text from Gemini response
                $responseText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
                
                // Gemini sometimes wraps JSON in markdown blocks even with JSON mime type
                $responseText = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $responseText);
                
                $content = json_decode($responseText, true);
                $tokens = $data['usageMetadata']['totalTokenCount'] ?? null;

                // Save or update AI Grading Result
                $result = AIGradingResult::updateOrCreate(
                    ['submission_id' => $submission->id],
                    [
                        'suggested_score' => $content['suggested_score'] ?? null,
                        'feedback' => $content['feedback'] ?? null,
                        'rubric_breakdown' => $content['rubric_breakdown'] ?? null,
                        'model_used' => 'gemini-2.5-flash',
                        'tokens_used' => $tokens,
                    ]
                );

                return ['status' => 'success', 'data' => $result];
            } else {
                Log::error('Gemini Grading Failed', ['response' => $response->body()]);
                return ['status' => 'error', 'message' => 'AI grading failed. Please try again.'];
            }
        } catch (\Exception $e) {
            Log::error('Gemini Grading Exception', ['message' => $e->getMessage()]);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}

