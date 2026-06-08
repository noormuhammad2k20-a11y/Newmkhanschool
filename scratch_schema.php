<?php
use Illuminate\Support\Facades\Schema;

$tables = ['teacher_attendances', 'leave_requests'];
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Table $table exists with columns:\n";
        print_r(Schema::getColumnListing($table));
    } else {
        echo "Table $table DOES NOT exist.\n";
    }
}
