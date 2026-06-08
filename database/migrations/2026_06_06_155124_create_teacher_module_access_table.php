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
        Schema::create('teacher_module_access', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id');
            $table->string('module_name', 100);
            $table->timestamps();
            
            $table->unique(['teacher_id', 'module_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_module_access');
    }
};
