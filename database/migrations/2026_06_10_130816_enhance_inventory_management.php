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
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory_categories');

        // Add new columns to existing inventory table
        if (Schema::hasTable('inventory')) {
            Schema::table('inventory', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory', 'unit')) {
                    $table->string('unit', 50)->nullable();
                }
                if (!Schema::hasColumn('inventory', 'min_stock_alert')) {
                    $table->integer('min_stock_alert')->default(0);
                }
                if (!Schema::hasColumn('inventory', 'purchase_price')) {
                    $table->decimal('purchase_price', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('inventory', 'supplier')) {
                    $table->string('supplier', 100)->nullable();
                }
                if (!Schema::hasColumn('inventory', 'location')) {
                    $table->string('location', 100)->nullable();
                }
            });
        }

        // Create inventory_categories table
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedBigInteger('school_id')->nullable();
            $table->timestamps();
        });

        // Create inventory_transactions table
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('inventory_id');
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->integer('quantity');
            $table->string('reason', 255);
            $table->string('reference_no', 100)->nullable();
            $table->integer('performed_by');
            $table->unsignedBigInteger('school_id')->nullable();
            $table->timestamps();

            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory_categories');

        if (Schema::hasTable('inventory')) {
            Schema::table('inventory', function (Blueprint $table) {
                $table->dropColumn(['unit', 'min_stock_alert', 'purchase_price', 'supplier', 'location']);
            });
        }
    }
};
