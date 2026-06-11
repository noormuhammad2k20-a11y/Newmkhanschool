<?php
$viewsDir = __DIR__ . '/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$count = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && strpos($file->getFilename(), '.blade.php') !== false) {
        $content = file_get_contents($file->getPathname());
        
        // Match @if(session('success')) ... @endif or @if(session('error')) ... @endif
        $pattern = '/\s*@if\s*\(\s*session\(\'(?:success|error)\'\)\s*\).*?@endif\s*/s';
        
        $newContent = preg_replace($pattern, "\n\n", $content);
        
        // Also match @if ($message = Session::get('success')) ... @endif
        $pattern2 = '/\s*@if\s*\(\$message\s*=\s*Session::get\(\'(?:success|error)\'\)\s*\).*?@endif\s*/s';
        $newContent = preg_replace($pattern2, "\n\n", $newContent);

        if ($content !== $newContent) {
            file_put_contents($file->getPathname(), ltrim($newContent));
            $count++;
            echo "Cleaned up: " . $file->getPathname() . "\n";
        }
    }
}

echo "Done! Cleaned up $count files.\n";
