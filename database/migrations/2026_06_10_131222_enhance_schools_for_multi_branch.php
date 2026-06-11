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
        Schema::dropIfExists('branch_settings');

        // Enhance existing schools table for multi-branch
        if (Schema::hasTable('schools')) {
            Schema::table('schools', function (Blueprint $table) {
                if (!Schema::hasColumn('schools', 'branch_code')) {
                    $table->string('branch_code', 50)->nullable()->unique();
                }
                if (!Schema::hasColumn('schools', 'parent_school_id')) {
                    $table->integer('parent_school_id')->nullable();
                }
                if (!Schema::hasColumn('schools', 'is_main_branch')) {
                    $table->boolean('is_main_branch')->default(false);
                }
                if (!Schema::hasColumn('schools', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }

        // Create branch_settings table
        Schema::create('branch_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->string('setting_key', 100);
            $table->text('setting_value')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->unique(['school_id', 'setting_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_settings');
        
        if (Schema::hasTable('schools')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->dropColumn(['branch_code', 'parent_school_id', 'is_main_branch', 'is_active']);
            });
        }
    }
};
