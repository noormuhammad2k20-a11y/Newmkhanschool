<?php
$user = \App\Models\User::find(144);
auth()->login($user);
$response = app()->make('Illuminate\Contracts\Http\Kernel')->handle(Illuminate\Http\Request::create('/teacher/digital-learning/quizzes/2/results', 'GET'));
file_put_contents('test_results.html', $response->getContent());
echo "Done.";
