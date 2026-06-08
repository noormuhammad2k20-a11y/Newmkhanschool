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
        Schema::table('students', function (Blueprint $table) {
            $table->string('exam_roll', 20)->nullable();
            $table->string('class_admitted', 50)->nullable();
            $table->date('admission_date')->nullable();
            $table->string('previous_school', 100)->nullable();
            $table->string('placeofbirth', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('caste', 50)->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('current_school', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'exam_roll',
                'class_admitted',
                'admission_date',
                'previous_school',
                'placeofbirth',
                'address',
                'religion',
                'caste',
                'photo',
                'current_school'
            ]);
        });
    }
};
