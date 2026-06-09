<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ParentStudent;
use App\Models\AcademicYear;
use App\Models\ExamType;
use App\Models\ExamSchedule;
use App\Models\Mark;
use App\Models\TimetableVersion;
use App\Models\Timetable;
use App\Models\TeacherAssignment;

class FreshSchoolSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Truncate Tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('marks')->truncate();
        DB::table('timetables')->truncate();
        DB::table('timetable_versions')->truncate();
        DB::table('teacher_assignments')->truncate();
        DB::table('parent_students')->truncate();
        DB::table('exam_schedules')->truncate();
        DB::table('students')->truncate();
        DB::table('teachers')->truncate();
        DB::table('subjects')->truncate();
        DB::table('sections')->truncate();
        DB::table('classes')->truncate();
        DB::table('exam_types')->truncate();
        DB::table('academic_years')->truncate();
        
        // Remove all users except Super Admin (if id=1 is Super Admin)
        DB::table('users')->where('id', '>', 1)->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Base Data
        $schoolId = 1;
        $adminRoleId = 1; // Assuming 1 is Super Admin or Admin
        $teacherRoleId = 3;
        $studentRoleId = 4;
        $parentRoleId = 5;

        // Ensure Admin exists
        $admin = User::find(1);
        if (!$admin) {
            $admin = User::create([
                'id' => 1,
                'name' => 'System Admin',
                'email' => 'admin@school.com',
                'password_hash' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'school_id' => $schoolId,
                'status' => 'active'
            ]);
        }

        $academicYear = AcademicYear::create([
            'year' => '2026-2027',
            'start_date' => '2026-08-01',
            'end_date' => '2027-05-31',
            'is_active' => 1
        ]);

        $examType = ExamType::create([
            'name' => 'Final Term'
        ]);

        // 3. Classes & Sections & Subjects
        $classNames = ['Class 1', 'Class 2', 'Class 3'];
        $subjectNames = ['English', 'Mathematics', 'Science', 'Urdu', 'Computer'];
        
        $classes = [];
        $subjects = [];

        foreach ($classNames as $className) {
            $class = SchoolClass::create([
                'name' => $className,
                'school_id' => $schoolId,
            ]);

            $section = Section::create([
                'class_id' => $class->id,
                'name' => 'A',
                'school_id' => $schoolId
            ]);

            $classes[] = ['class' => $class, 'section' => $section];

            foreach ($subjectNames as $subName) {
                $subject = Subject::create([
                    'class_id' => $class->id,
                    'name' => $subName,
                    'code' => strtoupper(substr($subName, 0, 3)),
                ]);
                $subjects[$class->id][] = $subject;
            }
        }

        // 4. Teachers
        $teacherNames = ['Ali Khan', 'Fatima Ahmed', 'Usman Tariq', 'Aisha Syed', 'Bilal Malik'];
        $teachers = [];

        foreach ($teacherNames as $index => $name) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@school.com';
            
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password_hash' => Hash::make('password'),
                'role_id' => $teacherRoleId,
                'school_id' => $schoolId,
                'status' => 'active'
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'full_name' => $name,
                'email' => $email,
                'employee_number' => 'EMP' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'school_id' => $schoolId,
                'cnic' => rand(10000, 99999) . '-' . rand(1000000, 9999999) . '-' . rand(1, 9),
                'mobile' => '0300' . rand(1000000, 9999999),
            ]);
            
            $teachers[] = $teacher;
        }

        // Assign Teachers to Classes and Subjects
        // We have 5 teachers and 3 classes with 5 subjects each (15 total class-subjects)
        // Let's distribute subjects evenly
        $teacherIndex = 0;
        foreach ($classes as $classData) {
            $classId = $classData['class']->id;
            foreach ($subjects[$classId] as $subject) {
                $assignedTeacher = $teachers[$teacherIndex % count($teachers)];
                TeacherAssignment::create([
                    'teacher_id' => $assignedTeacher->id,
                    'class_id' => $classId,
                    'subject_id' => $subject->id
                ]);
                $teacherIndex++;
            }
        }

        // 5. Students & Parents
        $studentsPerClass = 6;
        $studentNames = [
            'Omar', 'Hasan', 'Muhammad', 'Hamza', 'Khalid', 'Zaid',
            'Zainab', 'Maryam', 'Khadija', 'Hafsa', 'Asma', 'Amina',
            'Sana', 'Hira', 'Zara', 'Salma', 'Ahmed', 'Tariq'
        ];

        $studentCount = 0;
        
        $timetableVersion = TimetableVersion::create([
            'name' => 'Main Schedule 2026',
            'status' => 'Published',
            'academic_year_id' => $academicYear->id,
            'created_by' => 1
        ]);

        foreach ($classes as $classData) {
            $class = $classData['class'];
            $section = $classData['section'];
            
            // Generate Timetable for this class
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $times = [
                ['08:00:00', '08:45:00'],
                ['08:45:00', '09:30:00'],
                ['09:30:00', '10:15:00'],
                ['10:30:00', '11:15:00'],
                ['11:15:00', '12:00:00']
            ];

            foreach ($days as $dayIndex => $day) {
                foreach ($times as $timeIndex => $time) {
                    $subject = $subjects[$class->id][$timeIndex % count($subjects[$class->id])];
                    $teacherAssignment = TeacherAssignment::where('class_id', $class->id)->where('subject_id', $subject->id)->first();
                    
                    Timetable::create([
                        'timetable_version_id' => $timetableVersion->id,
                        'class_id' => $class->id,
                        'section_id' => $section->id,
                        'section_id_ref' => $section->id,
                        'subject' => $subject->name,
                        'subject_id_ref' => $subject->id,
                        'teacher' => $teacherAssignment ? $teacherAssignment->teacher->full_name : 'N/A',
                        'teacher_id' => $teacherAssignment ? $teacherAssignment->teacher_id : null,
                        'day_of_week' => $day,
                        'start_time' => $time[0],
                        'end_time' => $time[1],
                        'room' => 'Room ' . $class->id
                    ]);
                }
            }

            // Create Exam Schedule for this class
            foreach ($subjects[$class->id] as $subject) {
                ExamSchedule::create([
                    'exam_type' => $examType->name,
                    'class_name' => $class->name,
                    'subject' => $subject->name,
                    'exam_date' => now()->addDays(10)->format('Y-m-d'),
                    'exam_time' => '09:00:00',
                    'status' => 'Scheduled',
                    'max_marks' => 100,
                    'passing_marks' => 40,
                    'class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $academicYear->id,
                    'school_id' => $schoolId
                ]);
            }

            $examSchedules = ExamSchedule::where('class_id', $class->id)->get();

            // Create Students
            for ($i = 0; $i < $studentsPerClass; $i++) {
                $firstName = $studentNames[$studentCount % count($studentNames)];
                $lastName = 'Student';
                $email = strtolower("student{$studentCount}@school.com");
                
                $user = User::create([
                    'name' => "$firstName $lastName",
                    'email' => $email,
                    'password_hash' => Hash::make('password'),
                    'role_id' => $studentRoleId,
                    'school_id' => $schoolId,
                    'status' => 'active'
                ]);

                $student = Student::create([
                    'user_id' => $user->id,
                    'school_id' => $schoolId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'admission_no' => 'ADM' . str_pad($studentCount + 1, 4, '0', STR_PAD_LEFT),
                    'current_class_id' => $class->id,
                    'current_section_id' => $section->id,
                    'gender' => 'Male', // simplified
                    'dob' => now()->subYears(10)->format('Y-m-d'),
                    'father_name' => 'Parent of ' . $firstName,
                    'father_cnic' => rand(10000, 99999) . '-' . rand(1000000, 9999999) . '-' . rand(1, 9),
                    'b_form_number' => rand(10000, 99999) . '-' . rand(1000000, 9999999) . '-' . rand(1, 9),
                    'status' => 'Regular'
                ]);

                // Create Parent
                $parentEmail = "parent{$studentCount}@school.com";
                $parentUser = User::create([
                    'name' => 'Parent of ' . $firstName,
                    'email' => $parentEmail,
                    'password_hash' => Hash::make('password'),
                    'role_id' => $parentRoleId,
                    'school_id' => $schoolId,
                    'status' => 'active'
                ]);

                ParentStudent::create([
                    'parent_user_id' => $parentUser->id,
                    'student_id' => $student->id
                ]);

                // Assign Marks
                foreach ($examSchedules as $examSchedule) {
                    $marksObtained = rand(40, 100);
                    $percentage = $marksObtained;
                    
                    Mark::create([
                        'student_id' => $student->id,
                        'subject_id' => $examSchedule->subject_id,
                        'exam_type_id' => $examType->id,
                        'exam_schedule_id' => $examSchedule->id,
                        'academic_year_id' => $academicYear->id,
                        'marks_obtained' => $marksObtained,
                        'total_marks' => 100,
                        'percentage' => $percentage,
                        'grade' => $percentage >= 80 ? 'A' : ($percentage >= 60 ? 'B' : 'C'),
                        'gpa' => $percentage >= 80 ? 4.0 : ($percentage >= 60 ? 3.0 : 2.0),
                        'is_pass' => 1,
                        'remarks' => 'Good'
                    ]);
                }

                $studentCount++;
            }
        }
    }
}
