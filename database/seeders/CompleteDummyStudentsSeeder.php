<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentStudent;
use App\Models\AcademicYear;
use App\Models\StudentAttendance;
use App\Models\ExamType;
use App\Models\ExamSchedule;
use App\Models\Mark;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\StudentLeaveRequest;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\Fee;
use App\Models\FeePaymentTransaction;
use App\Models\FeeReceipt;
use App\Models\StudentPromotion;
use App\Models\DocumentTemplate;
use App\Models\IssuedDocument;
use App\Models\Notification;
use App\Models\School;
use App\Models\TeacherAssignment;
use Faker\Factory as Faker;

class CompleteDummyStudentsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $schoolId = School::first()->id ?? 1;

        // Ensure roles exist
        $studentRole = Role::firstOrCreate(['name' => 'Student'], ['description' => 'Student Role']);
        $parentRole = Role::firstOrCreate(['name' => 'Parent'], ['description' => 'Parent Role']);
        $teacherRole = Role::firstOrCreate(['name' => 'Teacher'], ['description' => 'Teacher Role']);

        // Get Active Academic Year
        $academicYear = AcademicYear::where('is_active', 1)->first() ?? AcademicYear::firstOrCreate([
            'year' => '2026-2027',
            'start_date' => '2026-08-01',
            'end_date' => '2027-05-31',
            'is_active' => 1
        ]);
        
        $prevAcademicYear = AcademicYear::firstOrCreate([
            'year' => '2025-2026',
            'start_date' => '2025-08-01',
            'end_date' => '2026-05-31',
            'is_active' => 0
        ]);

        // Create Classes & Sections
        $class6 = SchoolClass::firstOrCreate(['name' => 'Class 6', 'school_id' => $schoolId]);
        $section6 = Section::firstOrCreate(['class_id' => $class6->id, 'name' => 'A', 'school_id' => $schoolId]);

        $class8 = SchoolClass::firstOrCreate(['name' => 'Class 8', 'school_id' => $schoolId]);
        $section8 = Section::firstOrCreate(['class_id' => $class8->id, 'name' => 'A', 'school_id' => $schoolId]);

        $classes = [
            ['class' => $class6, 'section' => $section6, 'students' => ['Student 1', 'Student 2', 'Student 3']],
            ['class' => $class8, 'section' => $section8, 'students' => ['Student 4', 'Student 5']],
        ];

        // Ensure a Teacher exists
        $teacherUser = User::firstOrCreate(['email' => 'teacher.dummy@school.com'], [
            'name' => 'Mr. Dummy Teacher',
            'password_hash' => Hash::make('password'),
            'role_id' => $teacherRole->id,
            'school_id' => $schoolId,
            'status' => 'active'
        ]);

        $teacher = Teacher::firstOrCreate(['user_id' => $teacherUser->id], [
            'full_name' => 'Mr. Dummy Teacher',
            'email' => 'teacher.dummy@school.com',
            'employee_number' => 'EMP-DUMMY-1',
            'school_id' => $schoolId,
            'gender' => 'Male',
            'dob' => '1980-01-01',
            'mobile' => '555-1010101',
            'status' => 'Active'
        ]);

        // Setup subjects and exams
        $examType = ExamType::firstOrCreate(['name' => 'Mid Term']);
        
        // Fee Setup
        $feeCategory = FeeCategory::firstOrCreate(['name' => 'Monthly Tuition Fee', 'school_id' => $schoolId]);
        $feeStructure6 = FeeStructure::firstOrCreate(['school_id' => $schoolId, 'fee_category_id' => $feeCategory->id, 'class_id' => $class6->id], ['amount' => 5000]);
        $feeStructure8 = FeeStructure::firstOrCreate(['school_id' => $schoolId, 'fee_category_id' => $feeCategory->id, 'class_id' => $class8->id], ['amount' => 6000]);

        // Document Template
        $certTemplate = DocumentTemplate::firstOrCreate(['slug' => 'character-certificate'], [
            'name' => 'Character Certificate',
            'design_type' => 'Standard',
            'content' => 'This is to certify that [Student Name] has good character.',
            'variables' => '["Student Name"]',
            'is_active' => 1
        ]);

        // Transport
        DB::table('transport_routes')->updateOrInsert(['route_code' => 'RT-DUMMY'], [
            'name' => 'Dummy Route',
            'vehicle_no' => 'XYZ-999',
            'driver_name' => 'John Driver',
            'capacity' => 50,
            'status' => 'Active',
            'created_at' => now()
        ]);
        $route = DB::table('transport_routes')->where('route_code', 'RT-DUMMY')->first();

        // Books
        DB::table('library_books')->updateOrInsert(['title' => 'Dummy Science Book'], [
            'author' => 'Science Author',
            'category' => 'Science',
            'status' => 'Available',
            'created_at' => now()
        ]);
        $book = DB::table('library_books')->where('title', 'Dummy Science Book')->first();

        foreach ($classes as $data) {
            $class = $data['class'];
            $section = $data['section'];

            // Subject
            $subject = Subject::firstOrCreate(['name' => 'Mathematics', 'class_id' => $class->id], [
                'code' => 'MATH'
            ]);

            TeacherAssignment::firstOrCreate([
                'teacher_id' => $teacher->id,
                'class_id' => $class->id,
                'subject_id' => $subject->id
            ]);

            $examSchedule = ExamSchedule::firstOrCreate([
                'exam_type' => $examType->name,
                'class_id' => $class->id,
                'subject_id' => $subject->id,
                'academic_year_id' => $academicYear->id
            ], [
                'exam_date' => now()->addDays(5)->format('Y-m-d'),
                'exam_time' => '09:00:00',
                'end_time' => '12:00:00',
                'max_marks' => 100,
                'passing_marks' => 40,
                'school_id' => $schoolId
            ]);

            $assignment = Assignment::firstOrCreate([
                'title' => "Math Homework " . $class->name,
                'class_id' => $class->id,
                'subject_id' => $subject->id,
            ], [
                'teacher_id' => $teacher->id,
                'description' => "Complete the exercises",
                'type' => 'homework',
                'due_date' => now()->addDays(2)->format('Y-m-d')
            ]);

            foreach ($data['students'] as $index => $studentName) {
                $emailSlug = strtolower(str_replace(' ', '', $studentName));
                
                // 1. User & Parent User
                $parentUser = User::firstOrCreate(['email' => "parent.{$emailSlug}@school.com"], [
                    'name' => "Parent of " . $studentName,
                    'password_hash' => Hash::make('password123'),
                    'role_id' => $parentRole->id,
                    'school_id' => $schoolId,
                    'status' => 'active'
                ]);

                $studentUser = User::firstOrCreate(['email' => "{$emailSlug}@school.com"], [
                    'name' => $studentName,
                    'password_hash' => Hash::make('password123'),
                    'role_id' => $studentRole->id,
                    'school_id' => $schoolId,
                    'status' => 'active'
                ]);

                // 2. Student Record
                $student = Student::updateOrCreate(['user_id' => $studentUser->id], [
                    'admission_no' => 'DUMMY-' . $class->id . '-' . rand(1000, 9999) . $index,
                    'exam_roll' => 'ROLL-' . $class->id . '-' . rand(100, 999) . $index,
                    'first_name' => explode(' ', $studentName)[0],
                    'last_name' => explode(' ', $studentName)[1] ?? 'Test',
                    'gender' => $faker->randomElement(['Male', 'Female']),
                    'dob' => $faker->dateTimeBetween('-15 years', '-10 years')->format('Y-m-d'),
                    'father_name' => "Father of " . $studentName,
                    'father_cnic' => '12345-1234567-' . rand(1,9),
                    'b_form_number' => '12345-1234567-' . rand(1,9),
                    'mobile_number' => $faker->phoneNumber,
                    'address' => $faker->address,
                    'religion' => 'Islam',
                    'current_class_id' => $class->id,
                    'current_section_id' => $section->id,
                    'class_admitted' => $class->name,
                    'admission_date' => now()->subMonths(6)->format('Y-m-d'),
                    'status' => 'Regular',
                    'school_id' => $schoolId
                ]);

                ParentStudent::firstOrCreate([
                    'parent_user_id' => $parentUser->id,
                    'student_id' => $student->id
                ]);

                // 3. Attendance
                for ($day = 1; $day <= 5; $day++) {
                    StudentAttendance::updateOrCreate([
                        'student_id' => $student->id,
                        'date' => now()->subDays($day)->format('Y-m-d')
                    ], [
                        'academic_year_id' => $academicYear->id,
                        'status' => $faker->randomElement(['P', 'P', 'P', 'A', 'L']),
                        'marked_by' => $teacherUser->id
                    ]);
                }

                // 4. Marks / Examinations
                $marks = rand(45, 95);
                Mark::updateOrCreate([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'exam_schedule_id' => $examSchedule->id,
                ], [
                    'exam_type_id' => $examType->id,
                    'academic_year_id' => $academicYear->id,
                    'marks_obtained' => $marks,
                    'total_marks' => 100,
                    'percentage' => $marks,
                    'grade' => $marks >= 80 ? 'A' : ($marks >= 60 ? 'B' : 'C'),
                    'is_pass' => $marks >= 40 ? 1 : 0,
                    'remarks' => 'Good effort.'
                ]);

                // 5. Assignment Submission
                AssignmentSubmission::updateOrCreate([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id
                ], [
                    'status' => 'Graded',
                    'marks_obtained' => rand(15, 20),
                    'teacher_feedback' => 'Well done'
                ]);

                // 6. Fees
                $feeAmount = $class->name == 'Class 6' ? 5000 : 6000;
                $fee = Fee::updateOrCreate([
                    'student_id' => $student->id,
                    'challan_no' => 'FEE-DUM-' . $student->id . '-' . time()
                ], [
                    'fee_category_id' => $feeCategory->id,
                    'amount' => $feeAmount,
                    'paid_amount' => $feeAmount,
                    'due_date' => now()->endOfMonth()->format('Y-m-d'),
                    'status' => 'Paid'
                ]);

                $transaction = FeePaymentTransaction::updateOrCreate([
                    'fee_id' => $fee->id,
                    'student_id' => $student->id,
                ], [
                    'gateway' => 'Cash',
                    'transaction_ref' => 'TXN-CASH-' . rand(1000, 9999),
                    'amount' => $feeAmount,
                    'status' => 'Success',
                    'paid_at' => now()
                ]);

                FeeReceipt::firstOrCreate([
                    'transaction_id' => $transaction->id
                ], [
                    'receipt_no' => 'REC-' . rand(10000, 99999),
                    'student_id' => $student->id,
                    'fee_id' => $fee->id,
                    'amount' => $feeAmount,
                    'generated_at' => now()
                ]);

                // 7. Issued Document (Certificates)
                IssuedDocument::firstOrCreate([
                    'student_id' => $student->id,
                    'template_id' => $certTemplate->id
                ], [
                    'uuid' => (string) Str::uuid(),
                    'document_no' => 'DOC-' . rand(1000, 9999),
                    'issued_by' => 1,
                    'purpose' => 'General',
                    'issued_at' => now()
                ]);

                // 8. Notifications
                Notification::firstOrCreate([
                    'user_id' => $studentUser->id,
                    'title' => 'Welcome to the new session'
                ], [
                    'type' => 'System',
                    'body' => 'We are glad to have you here.',
                    'is_read' => 0,
                    'created_at' => now()
                ]);
                
                Notification::firstOrCreate([
                    'user_id' => $parentUser->id,
                    'title' => 'Fee Paid Successfully'
                ], [
                    'type' => 'System',
                    'body' => 'Your recent fee payment was successful.',
                    'is_read' => 0,
                    'created_at' => now()
                ]);

                // 9. Leave Request
                StudentLeaveRequest::firstOrCreate([
                    'student_id' => $student->id,
                    'start_date' => now()->addDays(10)->format('Y-m-d')
                ], [
                    'leave_type' => 'Sick',
                    'end_date' => now()->addDays(11)->format('Y-m-d'),
                    'reason' => 'Doctor appointment',
                    'status' => 'Approved'
                ]);

                // 10. Transport
                DB::table('transport_students')->updateOrInsert([
                    'student_id' => $student->id,
                    'route_id' => $route->id ?? 1
                ], [
                    'stop_name' => 'Stop ' . rand(1, 5),
                    'status' => 'Boarded',
                    'created_at' => now()
                ]);

                // 11. Promotions (History)
                StudentPromotion::firstOrCreate([
                    'student_id' => $student->id,
                    'academic_year_id' => $prevAcademicYear->id,
                    'to_academic_year_id' => $academicYear->id
                ], [
                    'from_class_id' => $class->id, // Simplified for dummy data
                    'from_section_id' => $section->id,
                    'to_class_id' => $class->id,
                    'to_section_id' => $section->id,
                    'promotion_type' => 'Promoted',
                    'status' => 'success',
                    'promoted_by' => 1,
                    'remarks' => 'Promoted successfully in the dummy seeder.',
                    'school_id' => $schoolId,
                    'promoted_at' => now()->subMonths(1)
                ]);

                // 12. Audit Log
                DB::table('audit_logs')->insert([
                    'user_id' => 1,
                    'action' => 'created',
                    'model_type' => 'App\\Models\\Student',
                    'model_id' => $student->id,
                    'description' => 'Dummy student generated.',
                    'ip_address' => '127.0.0.1',
                    'created_at' => now()
                ]);
            }
        }
    }
}
