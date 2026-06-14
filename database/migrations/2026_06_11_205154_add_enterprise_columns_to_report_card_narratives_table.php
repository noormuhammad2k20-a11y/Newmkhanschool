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
        Schema::table('report_card_narratives', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('generated_by_ai');
            $table->integer('version')->default(1)->after('is_locked');
            $table->decimal('ai_confidence_score', 5, 2)->nullable()->after('version');
            $table->json('narrative_history')->nullable()->after('ai_confidence_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_card_narratives', function (Blueprint $table) {
            $table->dropColumn(['is_locked', 'version', 'ai_confidence_score', 'narrative_history']);
        });
    }
};
