<?php
$file = __DIR__ . '/resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// Replace existing dynamic classes to include a wildcard '*'
$pattern = "/request\(\)->routeIs\('([a-zA-Z0-9.]+)'\)/";
$replacement = "request()->routeIs('$1*')";
$content = preg_replace($pattern, $replacement, $content);

file_put_contents($file, $content);
echo "Updated sidebar wildcard classes.";
