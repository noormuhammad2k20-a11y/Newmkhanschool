<?php
// config/db.php

$host = '127.0.0.1';
$port = '3307';
$db   = 'NewSchool';
$user = 'root';
$pass = ''; // Default empty password, can be changed if needed
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // If the database doesn't exist yet, we can catch it or log it
    // For now we'll just throw the error, but this script will be used
    // after the database is created.
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
