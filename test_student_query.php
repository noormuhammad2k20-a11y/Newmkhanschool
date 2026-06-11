<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = App\Models\Student::where('id', 13)->first(); // Sana, class 3, section 3
echo "Student ID: {$student->id}, Class: {$student->current_class_id}, Section: {$student->current_section_id}\n";

$quizzes = App\Models\Quiz::with(['subject', 'creator'])
    ->where('is_active', 1)
    ->where('class_id', $student->current_class_id)
    ->where(function($q) use ($student) {
        $q->whereNull('section_id')
          ->orWhere('section_id', $student->current_section_id);
    })
    ->orderBy('created_at', 'desc')
    ->get();

echo "Quizzes found for this student: " . $quizzes->count() . "\n";
echo json_encode($quizzes);
