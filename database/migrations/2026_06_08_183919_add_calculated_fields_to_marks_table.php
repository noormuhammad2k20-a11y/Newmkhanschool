<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->unsignedInteger('exam_schedule_id')->nullable()->after('exam_type_id');
            $table->decimal('percentage', 5, 2)->nullable()->after('total_marks');
            $table->string('grade', 5)->nullable()->after('percentage');
            $table->decimal('gpa', 3, 2)->nullable()->after('grade');
            $table->boolean('is_pass')->nullable()->after('gpa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dropColumn(['exam_schedule_id', 'percentage', 'grade', 'gpa', 'is_pass']);
        });
    }
};
