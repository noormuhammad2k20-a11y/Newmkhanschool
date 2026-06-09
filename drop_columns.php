<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
try {
    DB::statement('ALTER TABLE digital_notes DROP COLUMN section_id, DROP COLUMN academic_year_id');
    echo "Dropped digital notes columns\n";
} catch (Exception $e) {
    echo "Digital notes: " . $e->getMessage() . "\n";
}

try {
    DB::statement('ALTER TABLE quizzes DROP COLUMN section_id, DROP COLUMN academic_year_id, DROP COLUMN passing_marks');
    echo "Dropped quizzes columns\n";
} catch (Exception $e) {
    echo "Quizzes: " . $e->getMessage() . "\n";
}
