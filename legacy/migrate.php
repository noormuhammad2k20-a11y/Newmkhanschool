<?php
$host = '127.0.0.1';
$port = '3307';
$user = 'root';
$pass = ''; // Default empty password

try {
    // Connect without database to create it
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // Execute the SQL script
    $pdo->exec($sql);
    
    echo "Database migration completed successfully.\n";
} catch (\PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>
