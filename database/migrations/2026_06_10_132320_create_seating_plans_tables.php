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
        Schema::dropIfExists('seating_assignments');
        Schema::dropIfExists('seating_plans');

        Schema::create('seating_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('teacher_id');
            $table->integer('rows')->default(5);
            $table->integer('cols')->default(6);
            $table->integer('school_id')->nullable();
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });

        Schema::create('seating_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seating_plan_id');
            $table->integer('student_id');
            $table->integer('row_num');
            $table->integer('col_num');
            $table->timestamps();

            $table->foreign('seating_plan_id')->references('id')->on('seating_plans')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->unique(['seating_plan_id', 'row_num', 'col_num']); // One student per seat
            $table->unique(['seating_plan_id', 'student_id']); // One seat per student in a plan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seating_assignments');
        Schema::dropIfExists('seating_plans');
    }
};
