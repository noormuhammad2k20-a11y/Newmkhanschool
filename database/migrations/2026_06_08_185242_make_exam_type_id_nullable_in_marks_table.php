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
        Schema::table('marks', function (Blueprint $table) {
            // $table->dropForeign('marks_ibfk_3');
            $table->integer('exam_type_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            // Can't easily reverse without knowing the exact previous state, 
            // but we can make it not null again
            $table->integer('exam_type_id')->nullable(false)->change();
            $table->foreign('exam_type_id', 'marks_ibfk_3')->references('id')->on('exam_types')->onDelete('cascade');
        });
    }
};
