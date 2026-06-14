<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "VERSIONS:\n";
echo json_encode(\App\Models\TimetableVersion::all()) . "\n";
echo "TIMETABLES:\n";
echo json_encode(\App\Models\Timetable::count()) . " records\n";
