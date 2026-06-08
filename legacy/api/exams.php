<?php
// api/exams.php
require_once '../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Return dummy exam data
    $exams = [
        ['type' => 'Annual Examination', 'class' => 'Class XII (Sci)', 'subject' => 'Physics - Theory', 'date' => '15 Mar 2024', 'time' => '09:00 AM - 12:00 PM', 'status' => 'In Progress'],
        ['type' => 'Annual Examination', 'class' => 'Class XII (Arts)', 'subject' => 'History', 'date' => '15 Mar 2024', 'time' => '09:00 AM - 12:00 PM', 'status' => 'Scheduled'],
        ['type' => 'Midterm Assessment', 'class' => 'Class X', 'subject' => 'Mathematics', 'date' => '12 Mar 2024', 'time' => '10:00 AM - 01:00 PM', 'status' => 'Completed'],
        ['type' => 'Annual Examination', 'class' => 'Class XII (Com)', 'subject' => 'Accountancy', 'date' => '18 Mar 2024', 'time' => '09:00 AM - 12:00 PM', 'status' => 'Scheduled'],
        ['type' => 'Unit Test II', 'class' => 'Class VIII', 'subject' => 'General Science', 'date' => '20 Mar 2024', 'time' => '11:00 AM - 12:30 PM', 'status' => 'Scheduled']
    ];

    echo json_encode([
        'status' => 'success',
        'data' => $exams
    ]);
}
?>
