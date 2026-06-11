<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$quizzes = \App\Models\Quiz::whereIn('title', ['Special Math', 'Test Special Math'])->get();

echo "App Timezone: " . config('app.timezone') . "\n";
echo "Now (Laravel): " . now()->format('Y-m-d H:i:s') . "\n";
echo "Now (UTC): " . now()->utc()->format('Y-m-d H:i:s') . "\n";

foreach ($quizzes as $quiz) {
    echo "Quiz: " . $quiz->title . "\n";
    echo "  start_at: " . $quiz->start_at . "\n";
    echo "  end_at: " . $quiz->end_at . "\n";
    
    $start = \Carbon\Carbon::parse($quiz->start_at);
    $end = \Carbon\Carbon::parse($quiz->end_at);
    echo "  start_at (parsed): " . $start->format('Y-m-d H:i:s') . "\n";
    echo "  end_at (parsed): " . $end->format('Y-m-d H:i:s') . "\n";
    
    echo "  now() < start_at? " . (now() < $start ? 'Yes' : 'No') . "\n";
    echo "  now() > end_at? " . (now() > $end ? 'Yes' : 'No') . "\n";
}
