<?php
$c = new PDO('mysql:host=127.0.0.1;port=3307;dbname=NewSchool', 'root', '');
$q = $c->query("SELECT * FROM exam_types LIMIT 1");
if($q) {
    while($r = $q->fetch(PDO::FETCH_ASSOC)) {
        print_r($r);
    }
}
