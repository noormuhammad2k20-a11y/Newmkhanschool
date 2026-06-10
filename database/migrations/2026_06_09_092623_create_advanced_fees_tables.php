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
        Schema::create('fee_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id')->nullable();
            $table->unsignedBigInteger('fee_category_id');
            $table->integer('class_id');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('fee_category_id')->references('id')->on('fee_categories')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });

        Schema::table('fees', function (Blueprint $table) {
            if (!Schema::hasColumn('fees', 'fee_category_id')) {
                $table->unsignedBigInteger('fee_category_id')->nullable()->after('student_id');
                $table->foreign('fee_category_id')->references('id')->on('fee_categories')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            if (Schema::hasColumn('fees', 'fee_category_id')) {
                $table->dropForeign(['fee_category_id']);
                $table->dropColumn('fee_category_id');
            }
        });
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('fee_categories');
    }
};
