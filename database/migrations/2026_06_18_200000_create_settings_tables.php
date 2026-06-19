<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon', 50)->default('ri-settings-3-line');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_group_id')->constrained('setting_groups')->cascadeOnDelete();
            $table->string('key', 191)->unique();
            $table->longText('value')->nullable();
            $table->string('type', 30)->default('text');
            $table->string('label', 255);
            $table->text('description')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('setting_groups');
    }
};
