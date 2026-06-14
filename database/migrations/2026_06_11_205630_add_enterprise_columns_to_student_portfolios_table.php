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
        Schema::table('student_portfolios', function (Blueprint $table) {
            $table->json('skills_json')->nullable()->after('visibility');
            $table->integer('completion_score')->default(0)->after('skills_json');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_portfolios', function (Blueprint $table) {
            $table->dropColumn(['skills_json', 'completion_score']);
        });
    }
};
