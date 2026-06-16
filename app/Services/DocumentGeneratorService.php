<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\Student;
use App\Models\AcademicYear;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DocumentGeneratorService
{
    /**
     * Build all dynamic variables for certificate templates.
     */
    public function buildVariables(Student $student, array $extra = []): array
    {
        $schoolName = config('app.school_name', 'Government Boys Higher Secondary School Dhilyar');
        $schoolAddress = config('app.school_address', 'Taluka Khipro, District Sanghar');

        $studentName = trim($student->first_name . ' ' . $student->last_name);
        $fatherName = $student->father_name ?? 'N/A';
        $className = $student->currentClass?->name ?? 'N/A';
        $sectionName = $student->currentSection?->name ?? '';
        $admissionNo = $student->admission_no ?? 'N/A';

        $admissionDate = $student->admission_date
            ? Carbon::parse($student->admission_date)->format('d-m-Y')
            : (AcademicYear::where('is_active', 1)->value('start_date')
                ? Carbon::parse(AcademicYear::where('is_active', 1)->value('start_date'))->format('d-m-Y')
                : '[Admission Date]');

        $dob = $student->dob ? Carbon::parse($student->dob)->format('d-m-Y') : 'N/A';
        $dobWords = $student->dob ? $this->dateToWords($student->dob) : 'N/A';

        $issueDate = date('d-m-Y');
        $issueDateFormatted = date('F d, Y'); // e.g. "July 15, 2024"
        $leavingDate = now()->format('d-m-Y');

        $academicYear = $extra['academic_year'] ?? 'Current';
        $purpose = $extra['purpose'] ?? 'General purpose';
        $certificateNo = $extra['certificate_no'] ?? 'TBD';

        $address = (!empty($student->address) && strtoupper($student->address) !== 'N/A')
            ? $student->address : '';

        return [
            // School info
            'school_name'      => $schoolName,
            'school_address'   => $schoolAddress,

            // Student info
            'student_name'     => $studentName,
            'father_name'      => $fatherName,
            'class_name'       => $className,
            'section_name'     => $sectionName,
            'admission_no'     => $admissionNo,
            'admission_date'   => $admissionDate,
            'dob'              => $dob,
            'dob_words'        => $dobWords,
            'birth_place'      => $student->placeofbirth ?? 'N/A',
            'religion'         => $student->religion ?? 'Islam',
            'caste'            => $student->caste ?? 'N/A',
            'nationality'      => 'Pakistani',
            'gender'           => $student->gender ?? 'N/A',
            'previous_school'  => $student->previous_school ?? 'N/A',
            'class_admitted'   => $student->class_admitted ?? 'N/A',
            'address'          => $address,

            // Document info
            'certificate_no'   => $certificateNo,
            'issue_date'       => $issueDate,
            'issue_date_formatted' => $issueDateFormatted,
            'leaving_date'     => $leavingDate,
            'academic_year'    => $academicYear,
            'purpose'          => $purpose,

            // QR & Signature (injected later during final generation)
            'qr_code'          => $extra['qr_code'] ?? '',
            'signature'        => $extra['signature'] ?? '',
        ];
    }

    /**
     * Fill the template with student data.
     * If a Blade view exists for the template slug, it renders that view.
     * Otherwise falls back to the DB-stored content with mustache replacements.
     */
    public function fillTemplate(DocumentTemplate $template, Student $student, array $extra = []): string
    {
        $vars = $this->buildVariables($student, $extra);

        $viewName = 'certificates.' . $template->slug;

        if (view()->exists($viewName)) {
            // Render Blade view with all variables + the raw student/extra/template objects
            $content = view($viewName, array_merge($vars, [
                'student'  => $student,
                'extra'    => $extra,
                'template' => $template,
                'vars'     => $vars,
            ]))->render();

            return $content;
        }

        // Legacy fallback: replace {{variable}} placeholders in DB content
        $mustacheVars = [];
        foreach ($vars as $key => $value) {
            $mustacheVars['{{'.$key.'}}'] = $value;
        }

        return str_replace(array_keys($mustacheVars), array_values($mustacheVars), $template->content);
    }

    /**
     * Generate PDF and save to storage.
     */
    public function generatePDF(string $htmlContent, string $filename): string
    {
        $pdf = Pdf::loadHTML($htmlContent)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('isPhpEnabled', false)
            ->setOption('isFontSubsettingEnabled', true)
            ->setOption('defaultFont', 'helvetica');

        $path = "documents/{$filename}.pdf";

        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Enhance content using OpenAI API.
     */
    public function aiEnhance(string $content, string $docType): string
    {
        $apiKey = env('OPENAI_API_KEY');
        if (empty($apiKey)) {
            return $content;
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
                $enhancedContent = preg_replace('/^```html\s*|\s*```$/i', '', trim($enhancedContent));
                return $enhancedContent;
            }
        } catch (\Exception $e) {
            \Log::error('OpenAI Document Enhancement Error: ' . $e->getMessage());
        }

        return $content;
    }

    /**
     * Convert a date to words.
     * e.g. "2008-08-14" → "Fourteenth of August, Two Thousand and Eight"
     */
    private function dateToWords($date): string
    {
        try {
            $dt = Carbon::parse($date);
            $day = $dt->day;
            $month = $dt->format('F');
            $year = $dt->year;

            $ordinals = [
                1 => 'First', 2 => 'Second', 3 => 'Third', 4 => 'Fourth', 5 => 'Fifth',
                6 => 'Sixth', 7 => 'Seventh', 8 => 'Eighth', 9 => 'Ninth', 10 => 'Tenth',
                11 => 'Eleventh', 12 => 'Twelfth', 13 => 'Thirteenth', 14 => 'Fourteenth',
                15 => 'Fifteenth', 16 => 'Sixteenth', 17 => 'Seventeenth', 18 => 'Eighteenth',
                19 => 'Nineteenth', 20 => 'Twentieth', 21 => 'Twenty-First', 22 => 'Twenty-Second',
                23 => 'Twenty-Third', 24 => 'Twenty-Fourth', 25 => 'Twenty-Fifth',
                26 => 'Twenty-Sixth', 27 => 'Twenty-Seventh', 28 => 'Twenty-Eighth',
                29 => 'Twenty-Ninth', 30 => 'Thirtieth', 31 => 'Thirty-First',
            ];

            $dayWord = $ordinals[$day] ?? (string)$day;
            $yearWord = $this->yearToWords($year);

            return "{$dayWord} of {$month}, {$yearWord}";
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Convert a 4 digit year to words without using NumberFormatter.
     */
    private function yearToWords($year): string
    {
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        $year = (int)$year;
        
        if ($year >= 2000) {
            $words = 'Two Thousand';
            $remainder = $year - 2000;
            
            if ($remainder > 0) {
                $words .= ' and ';
                if ($remainder < 20) {
                    $words .= $ones[$remainder];
                } else {
                    $ten = (int)($remainder / 10);
                    $one = $remainder % 10;
                    $words .= $tens[$ten];
                    if ($one > 0) {
                        $words .= '-' . $ones[$one];
                    }
                }
            }
            return $words;
        } elseif ($year >= 1900) {
            $words = 'Nineteen Hundred';
            $remainder = $year - 1900;
            
            if ($remainder > 0) {
                $words .= ' and ';
                if ($remainder < 20) {
                    $words .= $ones[$remainder];
                } else {
                    $ten = (int)($remainder / 10);
                    $one = $remainder % 10;
                    $words .= $tens[$ten];
                    if ($one > 0) {
                        $words .= '-' . $ones[$one];
                    }
                }
            }
            return $words;
        }
        
        return (string)$year;
    }
}
