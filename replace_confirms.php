<?php

$dir = new RecursiveDirectoryIterator('d:/Xamp/htdocs/school/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

$count = 0;
foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;
    
    // Replace onsubmit="return confirm('Message')" with data-confirm="Message"
    $content = preg_replace('/onsubmit=[\"\']return\s+confirm\(([\"\'])(.*?)\1\);?[\"\']/i', 'data-confirm="$2"', $content);
    
    // Replace onclick="return confirm('Message')" with data-confirm-click="Message"
    $content = preg_replace('/onclick=[\"\']return\s+confirm\(([\"\'])(.*?)\1\);?[\"\']/i', 'data-confirm-click="$2"', $content);
    
    // Replace inline alert('msg') with window.UI.alert('Alert', 'msg') in script blocks and onclick handlers (where applicable, though global override should catch it, it's safer to use UI.alert directly if possible, or just leave it for the global override)
    
    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated $path\n";
        $count++;
    }
}
echo "Total files updated: $count\n";
