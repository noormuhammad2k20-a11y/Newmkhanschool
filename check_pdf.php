<?php
$content = file_get_contents('storage/app/public/documents/test_char_cert.pdf');
echo 'Count: ' . substr_count($content, '/Type /Page') . "\n";
// Also check for /Type /Pages
if (preg_match('/\/Count (\d+)/', $content, $matches)) {
    echo 'Count via /Count: ' . $matches[1] . "\n";
}
