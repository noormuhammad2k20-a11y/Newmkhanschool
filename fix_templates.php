<?php
$templates = App\Models\DocumentTemplate::all();
foreach($templates as $t) {
    $c = $t->content;
    $c = str_replace('min-height: 980px', 'min-height: 650px', $c);
    $c = str_replace('padding: 40px', 'padding: 25px', $c);
    $c = str_replace('margin-top: 60px', 'margin-top: 25px', $c);
    $c = str_replace('margin-top: 50px', 'margin-top: 25px', $c);
    $c = str_replace('font-size: 32px', 'font-size: 26px', $c);
    $c = str_replace('font-size: 22px', 'font-size: 18px', $c);
    $c = str_replace('font-size: 20px', 'font-size: 16px', $c);
    $c = str_replace('font-size: 16px', 'font-size: 14px', $c);
    $c = str_replace('padding: 8px 10px', 'padding: 4px 8px', $c);
    $c = str_replace('margin-bottom: 20px', 'margin-bottom: 10px', $c);
    $c = str_replace('margin-bottom: 15px', 'margin-bottom: 8px', $c);
    $c = str_replace('width: 120px; height: 120px; line-height: 120px;', 'width: 80px; height: 80px; line-height: 80px;', $c);
    $t->content = $c;
    $t->save();
}
echo "Templates updated for Landscape.\n";
