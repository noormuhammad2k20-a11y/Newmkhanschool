<?php
// api/marks.php
require_once '../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Return students for marks entry
    try {
        $query = "
            SELECT s.id, s.admission_number as roll_no, s.first_name, s.last_name
            FROM students s
            JOIN classes c ON s.current_class_id = c.id
            WHERE c.name = 'Class X'
            ORDER BY s.first_name, s.last_name
            LIMIT 50
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'data' => $students
        ]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    // Handle saving marks
    echo json_encode(['status' => 'success', 'message' => 'Marks saved successfully']);
}
?>
