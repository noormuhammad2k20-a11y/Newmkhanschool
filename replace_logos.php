<?php
$files = [
    'resources/views/certificates/transfer-certificate.blade.php',
    'resources/views/certificates/school-leaving-certificate.blade.php',
    'resources/views/certificates/official-transcript-of-marks.blade.php',
    'resources/views/certificates/certificate-of-participation.blade.php',
    'resources/views/certificates/certificate-of-achievement.blade.php',
    'resources/views/certificates/character-certificate.blade.php'
];

$replacement = "data:image/png;base64,{{ base64_encode(file_get_contents(base_path('transparent-image.png'))) }}";

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "Missing $file\n";
        continue;
    }
    $content = file_get_contents($file);
    $content = preg_replace('/src="data:image\/svg\+xml;utf8,[^"]+"/', 'src="' . $replacement . '"', $content);
    file_put_contents($file, $content);
    echo "Updated $file\n";
}
