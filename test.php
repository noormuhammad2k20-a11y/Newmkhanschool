<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fees = App\Models\Fee::with('category')->get();
echo 'Total fees: ' . count($fees) . "\n";
foreach($fees as $f) {
    echo "Fee ID: {$f->id}, cat_id: {$f->fee_category_id}, category_name: " . ($f->category->name ?? 'null') . ", fee_category string: {$f->fee_category}\n";
}
