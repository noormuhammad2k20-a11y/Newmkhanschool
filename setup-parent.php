<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Student;
use App\Models\ParentStudent;
use Illuminate\Support\Facades\Hash;

// Find a student
$studentUser = User::where('email', 'student1@school.com')->first();
$studentRecord = Student::where('user_id', $studentUser->id)->first();

// Create parent
$parentUser = clone $studentUser;
$parentUser = User::firstOrCreate(
    ['email' => 'parent1@school.com'],
    [
        'name' => 'Test Parent',
        'password_hash' => Hash::make('password'),
        'role_id' => 5, // Parent
        'school_id' => 1,
        'status' => 'Active',
        'email_verified_at' => now(),
    ]
);

// Link them
if ($studentRecord) {
    ParentStudent::firstOrCreate([
        'parent_user_id' => $parentUser->id,
        'student_id' => $studentRecord->id
    ]);
    echo "Successfully created Parent (parent1@school.com) linked to Student (student1@school.com)!\n";
} else {
    echo "Student record not found for student1@school.com\n";
}

