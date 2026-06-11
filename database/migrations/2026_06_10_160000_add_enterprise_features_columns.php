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
        if (Schema::hasTable('seating_plans')) {
            Schema::table('seating_plans', function (Blueprint $table) {
                if (!Schema::hasColumn('seating_plans', 'mode')) {
                    $table->enum('mode', ['Regular', 'Exam'])->default('Regular')->after('teacher_id');
                }
            });
        }

        if (Schema::hasTable('issued_documents')) {
            Schema::table('issued_documents', function (Blueprint $table) {
                if (!Schema::hasColumn('issued_documents', 'uuid')) {
                    $table->string('uuid', 36)->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('issued_documents', 'qr_code_path')) {
                    $table->string('qr_code_path', 255)->nullable()->after('pdf_path');
                }
            });
        }

        if (Schema::hasTable('document_templates')) {
            Schema::table('document_templates', function (Blueprint $table) {
                if (!Schema::hasColumn('document_templates', 'design_type')) {
                    $table->string('design_type', 50)->default('classic')->after('variables');
                }
                if (!Schema::hasColumn('document_templates', 'has_qr')) {
                    $table->boolean('has_qr')->default(false)->after('design_type');
                }
                if (!Schema::hasColumn('document_templates', 'has_signature')) {
                    $table->boolean('has_signature')->default(false)->after('has_qr');
                }
            });
        }

        if (Schema::hasTable('schools')) {
            Schema::table('schools', function (Blueprint $table) {
                if (!Schema::hasColumn('schools', 'principal_signature_path')) {
                    $table->string('principal_signature_path', 255)->nullable()->after('principal_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('seating_plans')) {
            Schema::table('seating_plans', function (Blueprint $table) {
                if (Schema::hasColumn('seating_plans', 'mode')) {
                    $table->dropColumn('mode');
                }
            });
        }

        if (Schema::hasTable('issued_documents')) {
            Schema::table('issued_documents', function (Blueprint $table) {
                if (Schema::hasColumn('issued_documents', 'uuid')) {
                    $table->dropColumn('uuid');
                }
                if (Schema::hasColumn('issued_documents', 'qr_code_path')) {
                    $table->dropColumn('qr_code_path');
                }
            });
        }

        if (Schema::hasTable('document_templates')) {
            Schema::table('document_templates', function (Blueprint $table) {
                if (Schema::hasColumn('document_templates', 'design_type')) {
                    $table->dropColumn('design_type');
                }
                if (Schema::hasColumn('document_templates', 'has_qr')) {
                    $table->dropColumn('has_qr');
                }
                if (Schema::hasColumn('document_templates', 'has_signature')) {
                    $table->dropColumn('has_signature');
                }
            });
        }

        if (Schema::hasTable('schools')) {
            Schema::table('schools', function (Blueprint $table) {
                if (Schema::hasColumn('schools', 'principal_signature_path')) {
                    $table->dropColumn('principal_signature_path');
                }
            });
        }
    }
};
