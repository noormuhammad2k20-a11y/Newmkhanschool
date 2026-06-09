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
        Schema::table('digital_notes', function (Blueprint $table) {
            $table->integer('section_id')->nullable()->after('class_id');
            $table->integer('academic_year_id')->nullable()->after('section_id');

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('section_id')->nullable()->after('class_id');
            $table->integer('academic_year_id')->nullable()->after('section_id');
            $table->integer('passing_marks')->default(0)->after('total_marks');

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_learning_tables', function (Blueprint $table) {
            //
        });
    }
};
