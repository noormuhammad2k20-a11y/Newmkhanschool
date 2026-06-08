<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Classes:\n";
print_r(Illuminate\Support\Facades\Schema::getColumnListing('classes'));

echo "Subjects:\n";
print_r(Illuminate\Support\Facades\Schema::getColumnListing('subjects'));
