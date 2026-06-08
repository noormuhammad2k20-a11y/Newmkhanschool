<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'NewSchool', 3307);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$tablesResult = $conn->query("SHOW TABLES");
$schema = [];
while($tableRow = $tablesResult->fetch_array()) {
    $tableName = $tableRow[0];
    $columnsResult = $conn->query("SHOW COLUMNS FROM `$tableName`");
    $columns = [];
    while($colRow = $columnsResult->fetch_assoc()) {
        $columns[] = $colRow;
    }
    $schema[$tableName] = $columns;
}

file_put_contents('schema.json', json_encode($schema, JSON_PRETTY_PRINT));
echo "Schema written to schema.json";
