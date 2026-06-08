<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();


$roles = DB::table('roles')->get();
$users = DB::table('users')->select('id', 'name', 'email', 'role_id')->limit(5)->get();

echo json_encode([
    'roles' => $roles,
    'users' => $users
], JSON_PRETTY_PRINT);
