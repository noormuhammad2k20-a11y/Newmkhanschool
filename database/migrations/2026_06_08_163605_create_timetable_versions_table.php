<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timetable_versions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['Draft', 'Pending Approval', 'Published', 'Archived'])->default('Draft');
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Clear existing orphaned timetables so we can add foreign key
        DB::table('timetables')->truncate();

        Schema::table('timetables', function (Blueprint $table) {
            $table->unsignedBigInteger('timetable_version_id')->after('id');
            $table->foreign('timetable_version_id')->references('id')->on('timetable_versions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign(['timetable_version_id']);
            $table->dropColumn('timetable_version_id');
        });
        
        Schema::dropIfExists('timetable_versions');
    }
};
