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
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'principal_name')) $table->string('principal_name')->nullable();
            if (!Schema::hasColumn('schools', 'phone')) $table->string('phone')->nullable();
            if (!Schema::hasColumn('schools', 'email')) $table->string('email')->nullable();
            if (!Schema::hasColumn('schools', 'city')) $table->string('city')->nullable();
            if (!Schema::hasColumn('schools', 'address')) $table->text('address')->nullable();
            if (!Schema::hasColumn('schools', 'logo')) $table->string('logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'principal_name',
                'phone',
                'email',
                'city',
                'address',
                'logo'
            ]);
        });
    }
};
