<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
Illuminate\Support\Facades\Schema::dropIfExists('student_badges');
Illuminate\Support\Facades\Schema::dropIfExists('portfolio_items');
Illuminate\Support\Facades\Schema::dropIfExists('student_portfolios');
Illuminate\Support\Facades\Schema::dropIfExists('tax_slips');
Illuminate\Support\Facades\Schema::dropIfExists('ai_predictions');
Illuminate\Support\Facades\DB::table('migrations')->whereIn('migration', [
    '2026_06_11_202214_create_student_badges_table',
    '2026_06_11_202531_create_student_portfolios_table',
    '2026_06_11_202604_create_portfolio_items_table',
    '2026_06_11_202713_create_tax_slips_table',
    '2026_06_11_205508_create_ai_predictions_table',
    '2026_06_11_205154_add_enterprise_columns_to_report_card_narratives_table',
    '2026_06_11_205630_add_enterprise_columns_to_student_portfolios_table',
    '2026_06_11_205829_add_slab_to_tax_slips_table'
])->delete();
echo "Cleaned up!\n";
