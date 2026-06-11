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
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->string('question_type', 20)->default('single')->after('quiz_id');
        });

        // Change the ENUM columns to VARCHAR using raw SQL to avoid DBAL issues
        try { \Illuminate\Support\Facades\DB::statement("ALTER TABLE quiz_questions MODIFY COLUMN correct_option VARCHAR(255)"); } catch (\Exception $e) {}
        try { \Illuminate\Support\Facades\DB::statement("ALTER TABLE quiz_answers MODIFY COLUMN selected_option VARCHAR(255)"); } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn('question_type');
        });
    }
};
