<?php
$c = new PDO('mysql:host=127.0.0.1;port=3307;dbname=NewSchool', 'root', '');
$q = $c->query("DESCRIBE marks");
if($q) {
    while($r = $q->fetch(PDO::FETCH_ASSOC)) {
        if ($r['Field'] == 'exam_type_id') print_r($r);
    }
}
