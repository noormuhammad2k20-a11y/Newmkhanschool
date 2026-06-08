<?php
$c = new PDO('mysql:host=127.0.0.1;port=3307;dbname=NewSchool', 'root', '');
$q = $c->query("SELECT * FROM exam_schedules");
if($q) {
    while($r = $q->fetch(PDO::FETCH_ASSOC)) {
        print_r($r);
    }
} else {
    echo "Query failed.\n";
}
