<?php
$schema = json_decode(file_get_contents('schema.json'), true);
$tables = ['seating_plans', 'document_templates', 'issued_documents', 'schools', 'school_branches', 'assignment_submissions', 'users'];
foreach ($tables as $t) {
    echo strtoupper($t) . ":\n";
    if(isset($schema[$t])) {
        foreach($schema[$t] as $col) {
            echo "  - " . $col['Field'] . " (" . $col['Type'] . ")\n";
        }
    } else {
        echo "  Not found\n";
    }
}
