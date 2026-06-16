<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<style>
@page { size: A4 portrait; margin: 10mm; }
body { margin: 0; padding: 0; font-family: 'Helvetica', 'Arial', sans-serif; }
.a4-wrapper {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    border: 3px double #1a365d;
}
.inner-padding {
    padding: 15mm;
    position: relative;
    height: 100%;
}
.signatures-area {
    position: absolute;
    bottom: 20px;
    left: 15mm;
    right: 15mm;
}
</style>
</head>
<body>
<div class="a4-wrapper">
    <div class="inner-padding">
        <h1>Hello World</h1>
        <p>This is a test to see if absolute wrapper causes 1 page.</p>
        
        <div class="signatures-area">
            Signatures go here
        </div>
    </div>
</div>
</body>
</html>
HTML;

$pdf = Pdf::loadHTML($html)
    ->setPaper('a4', 'portrait')
    ->setOption('isHtml5ParserEnabled', true);

Storage::disk('public')->put('documents/test_layout.pdf', $pdf->output());
echo "PDF generated.\n";
