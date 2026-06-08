<?php
// api/fees.php
require_once '../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        // Get metrics
        $metricsQuery = "
            SELECT 
                SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as total_collected,
                SUM(CASE WHEN status != 'Paid' THEN amount ELSE 0 END) as total_pending,
                COUNT(CASE WHEN status != 'Paid' THEN 1 END) as pending_students
            FROM fees
        ";
        $stmt = $pdo->prepare($metricsQuery);
        $stmt->execute();
        $metrics = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get transactions
        $txQuery = "
            SELECT f.*, s.first_name, s.last_name, c.name as class_name, sec.name as section_name
            FROM fees f
            JOIN students s ON f.student_id = s.id
            LEFT JOIN classes c ON s.current_class_id = c.id
            LEFT JOIN sections sec ON s.current_section_id = sec.id
            ORDER BY f.due_date DESC
            LIMIT 50
        ";
        $stmt = $pdo->prepare($txQuery);
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success', 
            'data' => [
                'metrics' => [
                    'collected' => $metrics['total_collected'] ?? 0,
                    'pending' => $metrics['total_pending'] ?? 0,
                    'pending_students' => $metrics['pending_students'] ?? 0
                ],
                'transactions' => $transactions
            ]
        ]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
