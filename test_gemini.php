<?php
$apiKey = 'AQ.Ab8RN6IdwsHb8iMdnHsYWIzROpq9no3NueINQGxFknSeRdL9xg';
$url = "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}";
$options = [
    'http' => [
        'method'  => 'GET',
        'ignore_errors' => true,
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo "Result: " . $result . "\n";
