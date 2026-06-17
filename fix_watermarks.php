<?php
$dir = 'resources/views/certificates/';
$files = glob($dir . '*.blade.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    $content = str_replace('transform: translate(-50%, -50%);', "margin-top: -225px;\n        margin-left: -225px;", $content);
    file_put_contents($file, $content);
    echo "Fixed watermark CSS in $file\n";
}
