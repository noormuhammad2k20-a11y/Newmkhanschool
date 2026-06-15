<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AuditFixDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:fix-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Phase 1 DB Audit Fixes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Phase 1 Database Fixes...");

        // 1.1 Create Missing School Admin User
        $this->info("1.1 Creating missing School Admin User...");
        $adminExists = DB::table('users')->where('role_id', 2)->exists();
        if (!$adminExists) {
            DB::insert("INSERT INTO `users` (`name`, `email`, `password_hash`, `role_id`, `school_id`, `status`, `created_at`)
            VALUES ('School Administrator', 'schooladmin@school.com', '\$2y\$12\$WQ.WD5ZaXNQREkCLuhcEhOlAjU6gagveAzoBRy12qMObWqxQcnwvi', 2, 1, 'active', NOW())");
        }

        // 1.3 Fix Fee Data Inconsistencies
        $this->info("1.3 Fixing Fee Data Inconsistencies...");
        DB::update("UPDATE `fees` SET `paid_amount` = 4000.00 WHERE `id` = 7 AND `status` = 'Paid' AND `paid_amount` = 0.00");
        DB::update("UPDATE `fees` SET `paid_amount` = `amount` WHERE `paid_amount` > `amount` AND `status` = 'Paid'");
        DB::update("UPDATE `fees` SET `paid_amount` = `amount` WHERE `paid_amount` = 0 AND `status` = 'Paid'");

        // 1.4 Populate Empty Financial Tables (Seed data)
        $this->info("1.4 Populating Financial Tables...");
        if (DB::table('bank_accounts')->count() == 0) {
            DB::insert("INSERT INTO `bank_accounts` (`school_id`, `account_name`, `account_number`, `bank_name`, `branch`, `initial_balance`, `current_balance`, `created_at`, `updated_at`) VALUES (1, 'Main School Account', '1234567890', 'Habib Bank', '001', 500000.00, 500000.00, NOW(), NOW())");
        }
        if (DB::table('expenses')->count() == 0) {
            if (DB::table('expense_categories')->count() == 0) {
                DB::insert("INSERT INTO `expense_categories` (`school_id`, `name`, `description`, `created_at`, `updated_at`) VALUES (1, 'Utilities', 'Electricity, Water, Gas', NOW(), NOW()), (1, 'Stationery', 'Office and school stationery', NOW(), NOW())");
            }
            // Fetch category IDs
            $cat1 = DB::table('expense_categories')->where('name', 'Utilities')->value('id') ?? 1;
            $cat2 = DB::table('expense_categories')->where('name', 'Stationery')->value('id') ?? 2;
            DB::insert("INSERT INTO `expenses` (`school_id`, `expense_category_id`, `amount`, `date`, `description`, `recorded_by`, `created_at`, `updated_at`) VALUES (1, ?, 15000.00, CURDATE(), 'Monthly Electricity Bill', 1, NOW(), NOW()), (1, ?, 5000.00, CURDATE(), 'Stationery items', 1, NOW(), NOW())", [$cat1, $cat2]);
        }
        if (DB::table('ledger_entries')->count() == 0) {
            DB::insert("INSERT INTO `ledger_entries` (`school_id`, `date`, `description`, `type`, `amount`, `created_at`, `updated_at`) VALUES (1, CURDATE(), 'Initial Deposit', 'Income', 500000.00, NOW(), NOW())");
        }

        // 1.5 Fix issued_documents UUID
        $this->info("1.5 Fixing issued_documents UUID...");
        DB::update("UPDATE `issued_documents` SET `uuid` = UUID() WHERE `uuid` IS NULL");

        // 1.6 Fix Teacher Module Access
        $this->info("1.6 Fixing Teacher Module Access...");
        DB::statement("
            INSERT INTO `teacher_module_access` (`teacher_id`, `module_name`, `created_at`, `updated_at`)
            SELECT t.id, m.module_name, NOW(), NOW()
            FROM `teachers` t
            CROSS JOIN (
            SELECT 'dashboard' AS module_name UNION SELECT 'attendance' UNION SELECT 'classes'
            UNION SELECT 'marks' UNION SELECT 'assignments' UNION SELECT 'timetable'
            UNION SELECT 'leave' UNION SELECT 'digital_notes' UNION SELECT 'quizzes'
            ) m
            WHERE NOT EXISTS (
            SELECT 1 FROM `teacher_module_access` tma
            WHERE tma.teacher_id = t.id AND tma.module_name = m.module_name
            )
        ");

        // 1.7 Fix Teacher Leave Date Validation data
        $this->info("1.7 Fixing bogus Teacher Leave Data...");
        DB::update("UPDATE `teacher_leave_requests` SET `start_date` = '2026-06-01', `end_date` = '2026-06-03' WHERE id = 1 AND `end_date` < `start_date` OR DATEDIFF(`end_date`, `start_date`) > 100");

        // 1.8 Fill Teacher & Student Incomplete Data
        $this->info("1.8 Filling Incomplete Data...");
        DB::update("UPDATE `teachers` SET `qualification`='B.Ed', `specialization`='General Education', `experience`=5 WHERE `id`=1");
        DB::update("UPDATE `teachers` SET `qualification`='M.A', `specialization`='Mathematics', `experience`=8 WHERE `id`=2");
        DB::update("UPDATE `teachers` SET `qualification`='B.Sc', `specialization`='Science', `experience`=3 WHERE `id`=3");
        DB::update("UPDATE `teachers` SET `qualification`='M.A', `specialization`='Urdu Literature', `experience`=10 WHERE `id`=4");
        DB::update("UPDATE `teachers` SET `qualification`='B.CS', `specialization`='Computer Science', `experience`=6 WHERE `id`=5");

        DB::update("UPDATE `students` SET `exam_roll`=CONCAT('2026-', LPAD(id, 4, '0')), `address`='Hyderabad, Sindh' WHERE `school_id`=1 AND `exam_roll` IS NULL");

        // 1.9 Generate Report Cards from Existing Marks
        $this->info("1.9 Generating Report Cards...");
        DB::statement("
            INSERT INTO `report_cards` (`student_id`, `academic_year_id`, `exam_type_id`, `total_obtained`, `total_max`, `percentage`, `grade`, `remarks`, `created_at`, `updated_at`)
            SELECT 
            m.student_id,
            m.academic_year_id,
            m.exam_type_id,
            SUM(m.marks_obtained) AS total_obtained,
            SUM(m.total_marks) AS total_max,
            ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) AS percentage,
            CASE 
                WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 90 THEN 'A+'
                WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 80 THEN 'A'
                WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 70 THEN 'B'
                WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 60 THEN 'C'
                WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 50 THEN 'D'
                ELSE 'F'
            END AS grade,
            'Auto-generated from marks' AS remarks,
            NOW(), NOW()
            FROM `marks` m
            GROUP BY m.student_id, m.academic_year_id, m.exam_type_id
        ");

        // 1.10 Add Missing Payroll Records
        $this->info("1.10 Adding Missing Payroll Records...");
        if (DB::table('payrolls')->count() <= 1) {
            DB::insert("INSERT INTO `payrolls` (`teacher_id`, `emp_id`, `name`, `role`, `basic_pay`, `allowances`, `deductions`, `net_salary`, `status`, `month_year`, `created_at`, `updated_at`) VALUES
            (1, 'EMP0001', 'Ali Khan', 'Teacher', 60000.00, 7000.00, 3000.00, 64000.00, 'Paid', '2026-06', NOW(), NOW()),
            (2, 'EMP0002', 'Fatima Ahmed', 'Teacher', 65000.00, 7000.00, 4000.00, 68000.00, 'Paid', '2026-06', NOW(), NOW()),
            (3, 'EMP0003', 'Usman Tariq', 'Teacher', 58000.00, 6000.00, 3000.00, 61000.00, 'Paid', '2026-06', NOW(), NOW()),
            (5, 'EMP0005', 'Bilal Malik', 'Teacher', 62000.00, 7000.00, 3500.00, 65500.00, 'Paid', '2026-06', NOW(), NOW())");
        }

        $this->info("Phase 1 Fixes Completed Successfully.");
    }
}
