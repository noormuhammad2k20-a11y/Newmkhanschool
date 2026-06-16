<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentGeneratorService
{
    /**
     * Fill the template with student data
     */
    public function fillTemplate(DocumentTemplate $template, Student $student, array $extra = []): string
    {
        $variables = [
            '{{school_name}}' => config('app.school_name', 'Government Boys Higher Secondary School Dhilyar'),
            '{{student_name}}' => trim($student->first_name . ' ' . $student->last_name),
            '{{father_name}}' => $student->father_name ?? 'N/A',
            '{{class_name}}' => $student->currentClass?->name ?? 'N/A',
            '{{admission_no}}' => $student->admission_no,
            '{{admission_date}}' => $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d-m-Y') : (\App\Models\AcademicYear::where('is_active', 1)->value('start_date') ? \Carbon\Carbon::parse(\App\Models\AcademicYear::where('is_active', 1)->value('start_date'))->format('d-m-Y') : '[Admission Date]'),
            '{{leaving_date}}' => now()->format('d-m-Y'),
            '{{academic_year}}' => $extra['academic_year'] ?? 'Current',
            '{{address}}' => (!empty($student->address) && strtoupper($student->address) !== 'N/A') ? $student->address : '',
            '{{purpose}}' => $extra['purpose'] ?? 'General purpose',
            '{{issue_date}}' => date('d-m-Y'),
            '{{dob}}' => $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d-m-Y') : 'N/A',
            '{{birth_place}}' => $student->placeofbirth ?? 'N/A',
            '{{religion}}' => $student->religion ?? 'N/A',
            '{{caste}}' => $student->caste ?? 'N/A',
            '{{previous_school}}' => $student->previous_school ?? 'N/A',
            '{{class_admitted}}' => $student->class_admitted ?? 'N/A',
            '{{certificate_no}}' => $extra['certificate_no'] ?? 'TBD',
            '{{qr_code}}' => $extra['qr_code'] ?? '',
            '{{signature}}' => $extra['signature'] ?? '',
        ];

        return str_replace(array_keys($variables), array_values($variables), $template->content);
    }

    /**
     * Generate PDF and save to storage
     */
    public function generatePDF(string $htmlContent, string $filename): string
    {
        $pdf = Pdf::loadHTML($htmlContent)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('isPhpEnabled', false)
            ->setOption('isFontSubsettingEnabled', true);

        $path = "documents/{$filename}.pdf";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return $path;
    }

    /**
     * Enhance content using OpenAI API
     */
    public function aiEnhance(string $content, string $docType): string
    {
        $apiKey = env('OPENAI_API_KEY');
        if (empty($apiKey)) {
            return $content; // Return original if no API key
        }

        try {
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "You are a professional school administrator. Improve the language of the provided {$docType} document to sound more formal and professional while maintaining the core facts, HTML structure and tags. Return ONLY the improved HTML content without any markdown formatting or explanations."
                        ],
                        [
                            'role' => 'user',
                            'content' => $content
                        ]
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $enhancedContent = $response->json('choices.0.message.content');
                // Remove markdown code blocks if OpenAI mistakenly added them
                $enhancedContent = preg_replace('/^```html\s*|\s*```$/i', '', trim($enhancedContent));
                return $enhancedContent;
            }
        } catch (\Exception $e) {
            \Log::error('OpenAI Document Enhancement Error: ' . $e->getMessage());
        }

        return $content;
    }
}
