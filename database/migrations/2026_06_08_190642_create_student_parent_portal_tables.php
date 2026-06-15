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
        Schema::dropIfExists('parent_students');
        Schema::create('parent_students', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_user_id');
            $table->integer('student_id');
            $table->timestamps();
            
            $table->unique(['parent_user_id', 'student_id']);
        });

        Schema::dropIfExists('student_leave_requests');
        Schema::create('student_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('leave_type', 100);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason')->nullable();
            $table->string('status', 50)->default('Pending'); // Pending, Approved, Rejected
            $table->timestamps();
        });

        Schema::dropIfExists('assignment_submissions');
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->integer('assignment_id');
            $table->integer('student_id');
            $table->string('file_path')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 50)->default('Submitted'); // Submitted, Late, Graded
            $table->decimal('marks_obtained', 5, 2)->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->timestamps();
            
            $table->unique(['assignment_id', 'student_id']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('student_leave_requests');
        Schema::dropIfExists('parent_students');
    }
};
