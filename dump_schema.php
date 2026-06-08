<?php
$c = new PDO('mysql:host=127.0.0.1;port=3307;dbname=NewSchool', 'root', '');
foreach(['marks', 'exam_schedules', 'students', 'teacher_assignments', 'exam_types'] as $t) {
    echo "\nTable: $t\n";
    $q = $c->query("DESCRIBE $t");
    if($q) {
        while($r = $q->fetch(PDO::FETCH_ASSOC)) {
            echo $r['Field'] . ' - ' . $r['Type'] . "\n";
        }
    } else {
        echo "Table not found.\n";
    }
}
