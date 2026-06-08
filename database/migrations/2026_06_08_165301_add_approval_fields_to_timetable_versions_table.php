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
        Schema::table('timetable_versions', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by')->nullable()->after('created_by');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->unsignedBigInteger('published_by')->nullable()->after('approved_at');
            $table->timestamp('published_at')->nullable()->after('published_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetable_versions', function (Blueprint $table) {
            $table->dropColumn(['approved_by', 'approved_at', 'published_by', 'published_at']);
        });
    }
};
