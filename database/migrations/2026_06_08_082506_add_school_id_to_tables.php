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
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'school_id')) {
                $table->integer('school_id')->default(1);
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            }
        });

        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'school_id')) {
                $table->integer('school_id')->default(1);
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            }
        });

        Schema::table('classes', function (Blueprint $table) {
            if (!Schema::hasColumn('classes', 'school_id')) {
                $table->integer('school_id')->default(1);
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            }
        });

        Schema::table('sections', function (Blueprint $table) {
            if (!Schema::hasColumn('sections', 'school_id')) {
                $table->integer('school_id')->default(1);
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
        });
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
        });
        Schema::table('classes', function (Blueprint $table) {
            if (Schema::hasColumn('classes', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
        });
        Schema::table('sections', function (Blueprint $table) {
            if (Schema::hasColumn('sections', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
        });
    }
};
