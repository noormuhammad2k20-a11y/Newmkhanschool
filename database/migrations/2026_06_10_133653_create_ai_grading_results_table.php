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
        Schema::create('ai_grading_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->decimal('suggested_score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->json('rubric_breakdown')->nullable();
            $table->string('model_used')->default('gpt-4');
            $table->integer('tokens_used')->nullable();
            $table->timestamps();

            // Depending on the name of the submission table in Laravel, let's assume 'assignment_submissions' is used.
            $table->foreign('submission_id')->references('id')->on('assignment_submissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_grading_results');
    }
};
