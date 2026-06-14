<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categories = App\Models\FeeCategory::pluck('name', 'id')->toArray();
echo "Fee Categories:\n";
print_r($categories);

$structures = App\Models\FeeStructure::with('category')->get();
echo "\nFee Structures:\n";
foreach($structures as $s) {
    echo "ID: {$s->id}, Class: {$s->class_id}, Cat: " . ($s->category->name ?? 'N/A') . " ({$s->fee_category_id}), Amount: {$s->amount}\n";
}
