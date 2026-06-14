<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$columns = Illuminate\Support\Facades\Schema::getColumnListing('document_templates');
print_r($columns);
