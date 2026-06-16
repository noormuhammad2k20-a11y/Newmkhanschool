<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyRichStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Setup base data
        $schoolId = \App\Models\School::first()->id ?? 1;
        $role = \App\Models\Role::where('name', 'Student')->first();
        if (!$role) {
            $this->command->error("Student role not found!");
            return;
        }

        // Get or Create Class and Section
        $class = \App\Models\SchoolClass::firstOrCreate(
            ['name' => '10th Grade', 'school_id' => $schoolId]
        );
        $section = \App\Models\Section::firstOrCreate(
            ['name' => 'A', 'class_id' => $class->id, 'school_id' => $schoolId]
        );

        // Subjects
        $subjects = ['Mathematics', 'Science', 'English', 'History', 'Computer Science'];
        $subjectIds = [];
        foreach ($subjects as $sub) {
            $subject = \App\Models\Subject::firstOrCreate(
                ['name' => $sub, 'class_id' => $class->id],
                ['code' => strtoupper(substr($sub, 0, 3))]
            );
            $subjectIds[] = $subject->id;
        }

        // 1. Create User
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'rich.student@example.com'],
            [
                'name' => 'Rich Test Student',
                'password_hash' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role_id' => $role->id,
                'school_id' => $schoolId,
                'status' => 'active',
                'created_at' => now(),
            ]
        );

        // 2. Create Student
        $student = \App\Models\Student::updateOrCreate(
            ['user_id' => $user->id],
            [
                'admission_no' => 'ADM-' . rand(1000, 9999),
                'first_name' => 'Rich',
                'last_name' => 'Test Student',
                'gender' => 'Male',
                'dob' => '2010-05-15',
                'father_name' => 'Mr. Parent',
                'mobile_number' => '555-123-4567',
                'current_class_id' => $class->id,
                'current_section_id' => $section->id,
                'status' => 'Regular',
                'admission_date' => now()->subYears(2),
                'created_at' => now(),
            ]
        );

        $academicYearId = \App\Models\AcademicYear::where('is_active', 1)->value('id') ?? 1;

        // 3. Create Attendances (Last 10 days)
        for ($i = 0; $i < 10; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            if (now()->subDays($i)->isWeekend()) continue;
            
            \App\Models\StudentAttendance::updateOrCreate(
                ['student_id' => $student->id, 'date' => $date],
                [
                    'status' => rand(1, 10) > 2 ? 'P' : 'A',
                    'marked_by' => 1,
                    'academic_year_id' => $academicYearId,
                    'created_at' => now(),
                ]
            );
        }

        // 4. Create Exam & Marks
        $examType = \App\Models\ExamType::firstOrCreate(
            ['name' => 'Final Examination']
        );
        
        $examSchedules = [];
        foreach ($subjectIds as $subId) {
            $schedule = \App\Models\ExamSchedule::firstOrCreate(
                ['class_id' => $class->id, 'subject_id' => $subId, 'exam_type' => $examType->name],
                [
                    'exam_date' => now()->subDays(15)->format('Y-m-d'),
                    'exam_time' => '09:00',
                    'end_time' => '12:00',
                    'max_marks' => 100,
                    'passing_marks' => 40,
                    'school_id' => $schoolId,
                    'academic_year_id' => $academicYearId,
                ]
            );
            $examSchedules[] = clone collect($schedule); // keep as simple array for later or model

            \App\Models\Mark::updateOrCreate(
                ['student_id' => $student->id, 'subject_id' => $subId, 'exam_type_id' => $examType->id],
                [
                    'exam_schedule_id' => $schedule->id,
                    'academic_year_id' => $academicYearId,
                    'marks_obtained' => rand(65, 98),
                    'total_marks' => 100,
                    'percentage' => 85,
                    'grade' => ['A', 'B', 'A+'][rand(0, 2)],
                    'is_pass' => true,
                    'remarks' => 'Good performance',
                    'created_at' => now(),
                ]
            );
        }

        // 5. Create Assignments & Submissions
        for ($i = 1; $i <= 3; $i++) {
            $assignment = \App\Models\Assignment::firstOrCreate(
                ['title' => "Math Homework $i", 'class_id' => $class->id],
                [
                    'subject_id' => $subjectIds[0],
                    'teacher_id' => 1,
                    'description' => "Complete exercises for chapter $i",
                    'type' => 'homework',
                    'due_date' => now()->subDays($i * 5)->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            \App\Models\AssignmentSubmission::updateOrCreate(
                ['assignment_id' => $assignment->id, 'student_id' => $student->id],
                [
                    'status' => 'graded',
                    'marks_obtained' => rand(15, 20),
                    'teacher_feedback' => 'Well done',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 6. Create Leave Requests
        \App\Models\StudentLeaveRequest::firstOrCreate(
            ['student_id' => $student->id, 'reason' => 'Family Event'],
            [
                'leave_type' => 'casual',
                'start_date' => now()->subDays(15)->format('Y-m-d'),
                'end_date' => now()->subDays(14)->format('Y-m-d'),
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info("Rich Test Student created successfully!");
        $this->command->info("Email: rich.student@example.com");
        $this->command->info("Student Name: Rich Test Student");
    }
}
