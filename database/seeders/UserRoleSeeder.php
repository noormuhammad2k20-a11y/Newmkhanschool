<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles (from check.php we know 1=Super Admin, 3=Teacher, 4=Student)
        $adminRoleId = DB::table('roles')->where('name', 'Super Admin')->value('id') ?? 1;
        $teacherRoleId = DB::table('roles')->where('name', 'Teacher')->value('id') ?? 3;
        $studentRoleId = DB::table('roles')->where('name', 'Student')->value('id') ?? 4;

        // Admin User
        $adminId = DB::table('users')->insertGetId([
            'name' => 'System Admin',
            'email' => 'admin@school.com',
            'password_hash' => Hash::make('password'),
            'role_id' => $adminRoleId,
            'status' => 'active'
        ]);

        // Teacher User
        $teacherId = DB::table('users')->insertGetId([
            'name' => 'Demo Teacher',
            'email' => 'teacher@school.com',
            'password_hash' => Hash::make('password'),
            'role_id' => $teacherRoleId,
            'status' => 'active'
        ]);

        // Link to a teacher profile (create one if needed)
        DB::table('teachers')->updateOrInsert(
            ['email' => 'teacher@school.com'],
            [
                'user_id' => $teacherId,
                'full_name' => 'Demo Teacher',
                'employee_number' => 'EMP' . rand(1000, 9999),
                'mobile' => '1234567890'
            ]
        );

        // Student User
        $studentId = DB::table('users')->insertGetId([
            'name' => 'Demo Student',
            'email' => 'student@school.com',
            'password_hash' => Hash::make('password'),
            'role_id' => $studentRoleId,
            'status' => 'active'
        ]);

        // Link to a student profile
        $existingStudent = DB::table('students')->first();
        if ($existingStudent) {
            DB::table('students')->where('id', $existingStudent->id)->update([
                'user_id' => $studentId,
            ]);
        }
    }
}
