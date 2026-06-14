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
        Schema::create('report_card_narratives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_card_id')->constrained('report_cards')->onDelete('cascade');
            $table->text('strengths')->nullable();
            $table->text('improvements')->nullable();
            $table->text('attendance_summary')->nullable();
            $table->text('teacher_comments')->nullable();
            $table->text('parent_guidance')->nullable();
            $table->text('next_term_goals')->nullable();
            $table->boolean('generated_by_ai')->default(true);
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_card_narratives');
    }
};
