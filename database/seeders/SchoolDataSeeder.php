<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class SchoolDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolId = 1;

        $subjectsTemplate = [
            ['name' => 'English', 'code' => 'ENG'],
            ['name' => 'Mathematics', 'code' => 'MATH'],
            ['name' => 'Science', 'code' => 'SCI'],
            ['name' => 'History', 'code' => 'HIST'],
            ['name' => 'Geography', 'code' => 'GEO'],
            ['name' => 'Computer Science', 'code' => 'CS'],
            ['name' => 'Physics', 'code' => 'PHY'],
            ['name' => 'Chemistry', 'code' => 'CHEM'],
            ['name' => 'Biology', 'code' => 'BIO'],
            ['name' => 'Physical Education', 'code' => 'PE'],
        ];

        // 1. Create Classes, Sections, and Subjects
        $classIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $class = SchoolClass::firstOrCreate([
                'name' => "Class $i",
                'school_id' => $schoolId,
            ]);

            $section = Section::firstOrCreate([
                'class_id' => $class->id,
                'name' => 'A',
            ]);

            foreach ($subjectsTemplate as $sub) {
                Subject::firstOrCreate([
                    'name' => $sub['name'],
                    'class_id' => $class->id,
                ], [
                    'code' => $sub['code'],
                ]);
            }

            $classIds[] = ['class_id' => $class->id, 'section_id' => $section->id];
        }

        // 2. Create Teachers
        $teacherNames = [
            'Tariq Mahmood', 'Aisha Khan', 'Hasan Ali', 'Fatima Syed', 
            'Bilal Ahmed', 'Zainab Shah', 'Usman Qureshi', 'Khadija Rahman',
            'Ibrahim Malik', 'Maryam Sheikh'
        ];

        foreach ($teacherNames as $index => $name) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@school.com';
            
            $user = User::updateOrCreate(['email' => $email], [
                'name' => $name,
                'password_hash' => Hash::make('password'),
                'role_id' => 3, // Teacher role
                'school_id' => $schoolId,
            ]);

            Teacher::updateOrCreate(['user_id' => $user->id], [
                'full_name' => $name,
                'email' => $email,
                'employee_number' => 'EMP' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
            ]);
        }

        // 3. Create Students (5 per class)
        $maleNames = ['Ali', 'Omar', 'Hasan', 'Muhammad', 'Ahmed', 'Tariq', 'Bilal', 'Hamza', 'Khalid', 'Zaid', 'Usman', 'Ibrahim'];
        $femaleNames = ['Fatima', 'Aisha', 'Zainab', 'Maryam', 'Khadija', 'Hafsa', 'Asma', 'Amina', 'Salma', 'Zara', 'Hira', 'Sana'];
        $surnames = ['Khan', 'Ahmed', 'Ali', 'Rahman', 'Syed', 'Malik', 'Shah', 'Qureshi', 'Sheikh', 'Ansari'];

        $studentIndex = 1;
        foreach ($classIds as $classInfo) {
            for ($j = 1; $j <= 5; $j++) {
                $isFemale = ($j % 2 == 0);
                $firstName = $isFemale ? $femaleNames[array_rand($femaleNames)] : $maleNames[array_rand($maleNames)];
                $lastName = $surnames[array_rand($surnames)];
                $fullName = "$firstName $lastName";
                $email = strtolower("student{$studentIndex}@school.com");

                $user = User::updateOrCreate(['email' => $email], [
                    'name' => $fullName,
                    'password_hash' => Hash::make('password'),
                    'role_id' => 4, // Student role
                    'school_id' => $schoolId,
                ]);

                Student::updateOrCreate(['user_id' => $user->id], [
                    'school_id' => $schoolId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'admission_no' => 'ADM' . str_pad($studentIndex, 4, '0', STR_PAD_LEFT),
                    'current_class_id' => $classInfo['class_id'],
                    'current_section_id' => $classInfo['section_id'],
                    'gender' => $isFemale ? 'Female' : 'Male',
                    'dob' => now()->subYears(10 + rand(0, 5))->format('Y-m-d'),
                    'father_name' => $maleNames[array_rand($maleNames)] . ' ' . $lastName,
                    'father_cnic' => rand(10000, 99999) . '-' . rand(1000000, 9999999) . '-' . rand(1, 9),
                    'b_form_number' => rand(10000, 99999) . '-' . rand(1000000, 9999999) . '-' . rand(1, 9),
                ]);

                $studentIndex++;
            }
        }
    }
}
