<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        // For security, usually this is in .env, but hardcoding as requested by the prompt 
        // to "add this api key (AQ.Ab8RN6I7_ePU5usGilkcSSRT4jfDcKD95FVbP9xucHgNQttYQA)"
        $this->apiKey = env('GEMINI_API_KEY', 'AQ.Ab8RN6I7_ePU5usGilkcSSRT4jfDcKD95FVbP9xucHgNQttYQA');
    }

    /**
     * Send a prompt to Gemini and get a response.
     */
    public function generateText($prompt)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
            }

            Log::error('Gemini API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate structured JSON from Gemini.
     */
    public function generateJson($prompt)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt . "\n\nRespond ONLY with valid JSON. Do not include markdown code blocks."]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $text = $data['candidates'][0]['content']['parts'][0]['text'];
                    // Clean markdown if present despite prompt
                    $text = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $text);
                    return json_decode($text, true);
                }
            }

            Log::error('Gemini API JSON Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini API JSON Exception: ' . $e->getMessage());
            return null;
        }
    }
}
