<?php
$content = file_get_contents('storage/app/public/documents/test_char_cert.pdf');
echo "test_char_cert.pdf Pages: " . preg_match_all('/\/Type\s*\/Page\b/', $content) . "\n";

$content2 = file_get_contents('storage/app/public/documents/test_layout.pdf');
echo "test_layout.pdf Pages: " . preg_match_all('/\/Type\s*\/Page\b/', $content2) . "\n";
