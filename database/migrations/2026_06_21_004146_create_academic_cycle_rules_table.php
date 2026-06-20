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
        Schema::create('academic_cycle_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->integer('exam_month')->nullable()->comment('e.g. 3 for March');
            $table->integer('result_processing_month')->nullable()->comment('e.g. 4 for April');
            $table->integer('promotion_window_start_month')->nullable()->comment('e.g. 4 for April');
            $table->integer('promotion_window_end_month')->nullable()->comment('e.g. 5 for May');
            $table->integer('next_session_start_month')->nullable()->comment('e.g. 8 for August');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_cycle_rules');
    }
};
