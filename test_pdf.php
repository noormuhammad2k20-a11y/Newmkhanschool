<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DocumentTemplate;
use App\Models\Student;
use App\Services\DocumentGeneratorService;

$template = DocumentTemplate::where('slug', 'character-certificate')->first();
$student = Student::first(); // Assuming there's at least one student

if (!$template || !$student) {
    die("Missing template or student.\n");
}

$generator = new DocumentGeneratorService();
$extra = [
    'academic_year' => '2024-2025',
    'certificate_no' => 'CH-20260616-00001',
    'purpose' => 'General purpose',
    'qr_code' => '',
    'signature' => ''
];

$html = $generator->fillTemplate($template, $student, $extra);
$path = $generator->generatePDF($html, 'test_char_cert');

echo "Generated PDF successfully at: " . $path . "\n";
