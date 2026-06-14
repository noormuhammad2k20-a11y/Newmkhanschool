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
        // 1. schools table
        Schema::table('schools', function (Blueprint $table) {
            if (Schema::hasColumn('schools', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('schools', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
            if (Schema::hasColumn('schools', 'branch_code')) {
                $table->dropColumn('branch_code');
            }
            if (Schema::hasColumn('schools', 'parent_school_id')) {
                $table->dropColumn('parent_school_id');
            }
            if (Schema::hasColumn('schools', 'is_main_branch')) {
                $table->dropColumn('is_main_branch');
            }
        });

        // Set code = 'SCH001' for id = 1 where code is empty
        DB::statement("UPDATE schools SET code = 'SCH001' WHERE id = 1 AND (code = '' OR code IS NULL)");

        // 2. timetables table
        Schema::table('timetables', function (Blueprint $table) {
            if (Schema::hasColumn('timetables', 'section_id')) {
                $table->dropColumn('section_id');
            }
            if (Schema::hasColumn('timetables', 'subject')) {
                $table->dropColumn('subject');
            }
        });

        // 3. marks table
        // First null out any invalid exam_schedule_id
        DB::statement('UPDATE marks SET exam_schedule_id = NULL WHERE exam_schedule_id NOT IN (SELECT id FROM exam_schedules)');

        Schema::table('marks', function (Blueprint $table) {
            $table->integer('exam_schedule_id')->nullable()->change();
            $table->foreign('exam_schedule_id')->references('id')->on('exam_schedules')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversing schema cleanup
    }
};
