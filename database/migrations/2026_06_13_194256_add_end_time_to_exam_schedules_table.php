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
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->string('end_time')->nullable()->after('exam_time');
        });

        // Backfill existing records: end_time = exam_time + 2 hours
        // For simple string parsing of times like "10:00" or "10:00:00"
        $exams = \DB::table('exam_schedules')->get();
        foreach ($exams as $exam) {
            if ($exam->exam_time) {
                try {
                    // Try to parse and add 2 hours
                    $time = \Carbon\Carbon::parse($exam->exam_time)->addHours(2)->format('H:i:s');
                    \DB::table('exam_schedules')->where('id', $exam->id)->update(['end_time' => $time]);
                } catch (\Exception $e) {
                    // Skip if invalid time format
                }
            }
        }

        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->string('status')->default('Scheduled')->after('end_time');
        });

        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropColumn('end_time');
        });
    }
};
