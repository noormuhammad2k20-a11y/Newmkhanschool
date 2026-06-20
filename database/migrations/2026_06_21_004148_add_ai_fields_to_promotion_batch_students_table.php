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
        Schema::table('promotion_batch_students', function (Blueprint $table) {
            $table->integer('eligibility_score')->nullable();
            $table->string('category')->nullable()->comment('eligible, conditional, defaulter');
            $table->json('risk_flags')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotion_batch_students', function (Blueprint $table) {
            $table->dropColumn(['eligibility_score', 'category', 'risk_flags']);
        });
    }
};
