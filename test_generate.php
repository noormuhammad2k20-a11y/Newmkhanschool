<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Disable CSRF for testing
$app->instance(Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, new class {
    public function handle($request, $next) { return $next($request); }
});

$response = $kernel->handle(
    $request = Illuminate\Http\Request::create("/admin/advanced/documents/generate", "POST", [
        "template_id" => 1,
        "student_id" => 1,
        "purpose" => "Test",
        "academic_year" => "2023-2024"
    ])
);
echo "Status: " . $response->getStatusCode() . "\n";
$content = $response->getContent();
if (strpos($content, "print_html") !== false) {
    echo "Success! print_html is present. Length: " . strlen($content);
} else {
    echo "Content: " . substr($content, 0, 500);
}

