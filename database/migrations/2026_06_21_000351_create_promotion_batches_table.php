<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('from_session_id');
            $table->unsignedBigInteger('to_session_id');
            $table->unsignedBigInteger('from_class_id');
            $table->unsignedBigInteger('to_class_id');
            $table->unsignedBigInteger('from_section_id')->nullable();
            $table->unsignedBigInteger('to_section_id')->nullable();
            $table->integer('total_students')->default(0);
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected', 'executed'])->default('pending_approval');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_batches');
    }
};
