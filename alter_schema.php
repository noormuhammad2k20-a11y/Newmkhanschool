<?php
$c = new PDO('mysql:host=127.0.0.1;port=3307;dbname=NewSchool', 'root', '');
try {
    $c->exec("ALTER TABLE marks DROP FOREIGN KEY marks_ibfk_3");
    echo "Dropped foreign key.\n";
} catch(Exception $e) {
    echo "Error dropping FK: " . $e->getMessage() . "\n";
}

try {
    $c->exec("ALTER TABLE marks MODIFY exam_type_id INT(11) NULL");
    echo "Made exam_type_id nullable.\n";
} catch(Exception $e) {
    echo "Error modifying column: " . $e->getMessage() . "\n";
}
