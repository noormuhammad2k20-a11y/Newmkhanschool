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
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('academic_year_id');
            $table->unsignedBigInteger('from_class_id');
            $table->unsignedBigInteger('from_section_id')->nullable();
            $table->unsignedBigInteger('to_class_id');
            $table->unsignedBigInteger('to_section_id')->nullable();
            $table->string('promotion_type')->default('Promoted');
            $table->unsignedBigInteger('promoted_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('promoted_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
    }
};
