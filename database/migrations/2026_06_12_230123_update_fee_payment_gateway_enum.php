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
        DB::statement("ALTER TABLE fee_payment_transactions MODIFY COLUMN gateway ENUM('JazzCash','EasyPaisa','Cash','Bank','Cheque','Bank Transfer','Online') NOT NULL DEFAULT 'Cash'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this perfectly might cause data loss if there are 'Cheque' records, so we leave it as is or revert to the old one if needed.
        DB::statement("ALTER TABLE fee_payment_transactions MODIFY COLUMN gateway ENUM('JazzCash','EasyPaisa','Cash','Bank') NOT NULL DEFAULT 'Cash'");
    }
};
