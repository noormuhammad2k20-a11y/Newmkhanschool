<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings_audit_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('setting_id');
            $table->string('setting_key', 191);
            $table->longText('old_value')->nullable();
            $table->longText('new_value')->nullable();
            $table->integer('changed_by')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('setting_key');
            $table->index('changed_by');
        });

        Schema::create('settings_backups', function (Blueprint $table) {
            $table->id();
            $table->string('file_path', 255);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->enum('type', ['manual', 'scheduled'])->default('manual');
            $table->enum('status', ['completed', 'failed', 'running'])->default('running');
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings_backups');
        Schema::dropIfExists('settings_audit_log');
    }
};
