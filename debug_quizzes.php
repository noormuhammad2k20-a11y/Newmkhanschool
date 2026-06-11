<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "QUIZZES:\n";
echo json_encode(App\Models\Quiz::all());
echo "\n\nSTUDENTS:\n";
echo json_encode(App\Models\Student::with(['currentClass', 'currentSection'])->get());
