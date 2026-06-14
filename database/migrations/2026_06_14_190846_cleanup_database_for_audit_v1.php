<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop Tables
        $tablesToDrop = [
            'payroll',
            'teacher_leaves',
            'branch_admins',
            'branch_settings',
            'school_branches',
            'ai_predictions',
            'attendance_anomalies',
            'attendance_patterns',
            'student_badges',
            'student_portfolios',
            'portfolio_items',
            'report_card_narratives',
            'exam_answers',
            'exam_attempts',
            'exam_questions',
            'online_exams',
            'inventory_categories',
            'seating_assignments',
            'seating_plans',
            'substitute_assignments',
            'teacher_leave_balances',
            'fee_payments',
            'messages',
            'student_promotions'
        ];

        // Disable foreign key checks before dropping to avoid constraint errors
        Schema::disableForeignKeyConstraints();

        foreach ($tablesToDrop as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();

        // 2. Delete Orphan Data
        // Delete records from student_attendances where student_id does not exist
        DB::statement('DELETE FROM student_attendances WHERE student_id NOT IN (SELECT id FROM students)');
        
        // Delete records from teacher_module_access where teacher_id does not exist
        DB::statement('DELETE FROM teacher_module_access WHERE teacher_id NOT IN (SELECT id FROM teachers)');

        // Fix FK constraint for teacher_module_access
        Schema::table('teacher_module_access', function (Blueprint $table) {
            $table->dropForeign('tma_teacher_id_fk');
            $table->foreign('teacher_id', 'tma_teacher_id_fk')->references('id')->on('teachers')->onDelete('cascade');
        });

        // Delete ghost users
        DB::statement('DELETE FROM users WHERE id IN (184, 185)');

        // Delete duplicate library books
        DB::statement('DELETE FROM library_books WHERE id IN (4, 5, 6)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We cannot reliably recreate dropped tables and deleted data in a single down method
        // without dumping the previous schema. This cleanup is considered permanent.
    }
};
