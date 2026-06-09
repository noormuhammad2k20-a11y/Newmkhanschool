<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('exam_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('exam_schedules', 'class_name')) {
                $table->dropColumn('class_name');
            }
            if (Schema::hasColumn('exam_schedules', 'subject')) {
                $table->dropColumn('subject');
            }
        });

        Schema::table('hostel_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('hostel_assignments', 'block')) {
                $table->dropColumn('block');
            }
            if (Schema::hasColumn('hostel_assignments', 'room')) {
                $table->dropColumn('room');
            }
        });

        try { DB::statement('ALTER TABLE assignment_submissions MODIFY COLUMN assignment_id bigint(20) UNSIGNED NOT NULL'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE assignment_submissions ADD CONSTRAINT assignment_submissions_assignment_id_foreign FOREIGN KEY (assignment_id) REFERENCES assignments (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE assignment_submissions ADD CONSTRAINT assignment_submissions_student_id_foreign FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE marks ADD CONSTRAINT marks_ibfk_3 FOREIGN KEY (exam_type_id) REFERENCES exam_types (id) ON DELETE SET NULL'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE marks MODIFY COLUMN exam_schedule_id bigint(20) UNSIGNED DEFAULT NULL'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE marks ADD CONSTRAINT marks_ibfk_5 FOREIGN KEY (exam_schedule_id) REFERENCES exam_schedules (id) ON DELETE SET NULL'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE parent_students ADD CONSTRAINT parent_students_parent_user_id_foreign FOREIGN KEY (parent_user_id) REFERENCES users (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE parent_students ADD CONSTRAINT parent_students_student_id_foreign FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE student_leave_requests ADD CONSTRAINT student_leave_requests_student_id_foreign FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE teacher_assignments ADD CONSTRAINT ta_teacher_id_fk FOREIGN KEY (teacher_id) REFERENCES teachers (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE teacher_assignments ADD CONSTRAINT ta_class_id_fk FOREIGN KEY (class_id) REFERENCES classes (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE teacher_assignments ADD CONSTRAINT ta_subject_id_fk FOREIGN KEY (subject_id) REFERENCES subjects (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE teacher_module_access ADD CONSTRAINT tma_teacher_id_fk FOREIGN KEY (teacher_id) REFERENCES teachers (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE report_cards ADD CONSTRAINT rc_exam_type_id_fk FOREIGN KEY (exam_type_id) REFERENCES exam_types (id) ON DELETE SET NULL'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE report_cards ADD CONSTRAINT rc_student_id_fk FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        try { DB::statement('ALTER TABLE report_cards ADD CONSTRAINT rc_academic_year_id_fk FOREIGN KEY (academic_year_id) REFERENCES academic_years (id) ON DELETE CASCADE'); } catch (\Exception $e) {}

        Schema::table('payroll', function (Blueprint $table) {
            if (!Schema::hasColumn('payroll', 'school_id')) {
                $table->integer('school_id')->default(1)->after('teacher_id');
            }
        });
        try { DB::statement('ALTER TABLE payroll ADD CONSTRAINT fk_payroll_school FOREIGN KEY (school_id) REFERENCES schools (id) ON DELETE CASCADE'); } catch (\Exception $e) {}

        Schema::table('inventory', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory', 'school_id')) {
                $table->integer('school_id')->default(1);
            }
        });
        try { DB::statement('ALTER TABLE inventory ADD CONSTRAINT inventory_school_id_fk FOREIGN KEY (school_id) REFERENCES schools (id) ON DELETE CASCADE'); } catch (\Exception $e) {}

        if (Schema::hasTable('assets')) {
            try { DB::statement('INSERT INTO inventory (asset_code, name, category, quantity, condition_status, school_id) SELECT asset_code, name, category, 1, condition_status, 1 FROM assets'); } catch (\Exception $e) {}
            Schema::dropIfExists('assets');
        }

        if (Schema::hasTable('transport_routes')) {
            Schema::table('transport_routes', function (Blueprint $table) {
                if (!Schema::hasColumn('transport_routes', 'school_id')) {
                    $table->integer('school_id')->default(1);
                }
            });
        }
        if (Schema::hasTable('hostel_rooms')) {
            Schema::table('hostel_rooms', function (Blueprint $table) {
                if (!Schema::hasColumn('hostel_rooms', 'school_id')) {
                    $table->integer('school_id')->default(1);
                }
            });
        }

        Schema::dropIfExists('leave_requests');

        try { DB::statement('ALTER TABLE teachers MODIFY COLUMN school_id int(11) NOT NULL DEFAULT 1'); } catch (\Exception $e) {}

        if (!Schema::hasTable('attendance_anomalies')) {
            Schema::create('attendance_anomalies', function (Blueprint $table) {
                $table->id();
                $table->integer('student_id')->nullable();
                $table->integer('teacher_id')->nullable();
                $table->enum('anomaly_type', ['fake_attendance','absence_pattern','late_pattern','consecutive_absent']);
                $table->text('description');
                $table->enum('severity', ['low','medium','high'])->default('low');
                $table->timestamp('detected_at')->useCurrent();
                $table->boolean('resolved')->default(false);
                $table->timestamp('resolved_at')->nullable();
                $table->integer('school_id')->default(1);
            });
            try { DB::statement('ALTER TABLE attendance_anomalies ADD CONSTRAINT aa_student_id_fk FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE attendance_anomalies ADD CONSTRAINT aa_teacher_id_fk FOREIGN KEY (teacher_id) REFERENCES teachers (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE attendance_anomalies ADD CONSTRAINT aa_school_id_fk FOREIGN KEY (school_id) REFERENCES schools (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        }

        if (!Schema::hasTable('attendance_patterns')) {
            Schema::create('attendance_patterns', function (Blueprint $table) {
                $table->id();
                $table->enum('entity_type', ['student','teacher']);
                $table->integer('entity_id');
                $table->enum('pattern_type', ['day_of_week','monthly','subject_specific']);
                $table->string('pattern_key', 50)->comment('e.g. Monday, January, Math');
                $table->decimal('absence_percentage', 5, 2)->default(0.00);
                $table->integer('total_days')->default(0);
                $table->integer('absent_days')->default(0);
                $table->timestamp('last_calculated')->useCurrent()->useCurrentOnUpdate();
                $table->integer('school_id')->default(1);
            });
            try { DB::statement('ALTER TABLE attendance_patterns ADD CONSTRAINT ap_school_id_fk FOREIGN KEY (school_id) REFERENCES schools (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        }

        if (!Schema::hasTable('digital_notes')) {
            Schema::create('digital_notes', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->string('file_path', 500)->nullable();
                $table->enum('file_type', ['pdf','doc','ppt','image','link','text'])->default('pdf');
                $table->string('external_url', 500)->nullable();
                $table->integer('subject_id');
                $table->integer('class_id');
                $table->bigInteger('uploaded_by')->unsigned()->comment('users.id');
                $table->boolean('is_public')->default(true);
                $table->integer('download_count')->default(0);
                $table->integer('school_id')->default(1);
                $table->timestamps();
            });
            try { DB::statement('ALTER TABLE digital_notes ADD CONSTRAINT dn_subject_id_fk FOREIGN KEY (subject_id) REFERENCES subjects (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE digital_notes ADD CONSTRAINT dn_class_id_fk FOREIGN KEY (class_id) REFERENCES classes (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE digital_notes ADD CONSTRAINT dn_uploaded_by_fk FOREIGN KEY (uploaded_by) REFERENCES users (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE digital_notes ADD CONSTRAINT dn_school_id_fk FOREIGN KEY (school_id) REFERENCES schools (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        }

        if (!Schema::hasTable('quizzes')) {
            Schema::create('quizzes', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->integer('subject_id');
                $table->integer('class_id');
                $table->bigInteger('created_by')->unsigned()->comment('users.id (teacher)');
                $table->integer('total_marks')->default(10);
                $table->integer('duration_minutes')->default(30);
                $table->dateTime('start_at')->nullable();
                $table->dateTime('end_at')->nullable();
                $table->boolean('is_active')->default(false);
                $table->integer('school_id')->default(1);
                $table->timestamps();
            });
            try { DB::statement('ALTER TABLE quizzes ADD CONSTRAINT qz_subject_id_fk FOREIGN KEY (subject_id) REFERENCES subjects (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE quizzes ADD CONSTRAINT qz_class_id_fk FOREIGN KEY (class_id) REFERENCES classes (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE quizzes ADD CONSTRAINT qz_created_by_fk FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE quizzes ADD CONSTRAINT qz_school_id_fk FOREIGN KEY (school_id) REFERENCES schools (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        }

        if (!Schema::hasTable('quiz_questions')) {
            Schema::create('quiz_questions', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('quiz_id')->unsigned();
                $table->text('question_text');
                $table->string('option_a', 500);
                $table->string('option_b', 500);
                $table->string('option_c', 500)->nullable();
                $table->string('option_d', 500)->nullable();
                $table->enum('correct_option', ['a','b','c','d']);
                $table->integer('marks')->default(1);
                $table->integer('order')->default(0);
            });
            try { DB::statement('ALTER TABLE quiz_questions ADD CONSTRAINT qq_quiz_id_fk FOREIGN KEY (quiz_id) REFERENCES quizzes (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        }

        if (!Schema::hasTable('quiz_attempts')) {
            Schema::create('quiz_attempts', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('quiz_id')->unsigned();
                $table->integer('student_id');
                $table->timestamp('started_at')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->decimal('score', 5, 2)->nullable();
                $table->integer('total_marks')->default(0);
                $table->decimal('percentage', 5, 2)->nullable();
                $table->enum('status', ['in_progress','submitted','timed_out'])->default('in_progress');
                $table->unique(['quiz_id', 'student_id'], 'unique_attempt');
            });
            try { DB::statement('ALTER TABLE quiz_attempts ADD CONSTRAINT qa_quiz_id_fk FOREIGN KEY (quiz_id) REFERENCES quizzes (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE quiz_attempts ADD CONSTRAINT qa_student_id_fk FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        }

        if (!Schema::hasTable('quiz_answers')) {
            Schema::create('quiz_answers', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('attempt_id')->unsigned();
                $table->bigInteger('question_id')->unsigned();
                $table->enum('selected_option', ['a','b','c','d'])->nullable();
                $table->boolean('is_correct')->nullable();
            });
            try { DB::statement('ALTER TABLE quiz_answers ADD CONSTRAINT qan_attempt_id_fk FOREIGN KEY (attempt_id) REFERENCES quiz_attempts (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
            try { DB::statement('ALTER TABLE quiz_answers ADD CONSTRAINT qan_question_id_fk FOREIGN KEY (question_id) REFERENCES quiz_questions (id) ON DELETE CASCADE'); } catch (\Exception $e) {}
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
    }
};
