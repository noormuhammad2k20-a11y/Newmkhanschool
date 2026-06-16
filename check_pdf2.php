<?php
$content = file_get_contents('storage/app/public/documents/test_layout2.pdf');
echo 'Pages: ' . substr_count($content, '/Type /Page') . "\n";
