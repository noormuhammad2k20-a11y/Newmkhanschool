<?php
$a = new \stdClass();
$a->student = null;
var_dump($a->student->user->name ?? 'Unknown');
