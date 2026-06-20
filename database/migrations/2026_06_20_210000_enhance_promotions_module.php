<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_promotions', function (Blueprint $table) {
            // Destination academic year (from session → to session)
            $table->unsignedBigInteger('to_academic_year_id')->nullable()->after('academic_year_id');

            // Promotion status tracking
            $table->string('status', 20)->default('success')->after('promotion_type');

            // Per-student error messages for failed promotions
            $table->text('error_message')->nullable()->after('remarks');

            // Batch ID to group promotions executed together
            $table->string('batch_id', 36)->nullable()->after('error_message');

            // Multi-school support
            $table->unsignedBigInteger('school_id')->nullable()->after('batch_id');

            // Prevent duplicate promotions: same student can't be promoted to same class in same session
            $table->unique(
                ['student_id', 'to_academic_year_id', 'to_class_id'],
                'unique_student_promotion_destination'
            );

            // Index for efficient history queries
            $table->index('batch_id');
            $table->index('school_id');
            $table->index('status');
            $table->index('promoted_at');
        });
    }

    public function down(): void
    {
        Schema::table('student_promotions', function (Blueprint $table) {
            $table->dropUnique('unique_student_promotion_destination');
            $table->dropIndex(['batch_id']);
            $table->dropIndex(['school_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['promoted_at']);
            $table->dropColumn([
                'to_academic_year_id',
                'status',
                'error_message',
                'batch_id',
                'school_id',
            ]);
        });
    }
};
