<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS teacher_assignments');
Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS teacher_module_access');
echo "Dropped tables successfully.\n";
