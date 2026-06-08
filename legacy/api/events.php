<?php
// api/events.php
require_once '../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM events ORDER BY start_date DESC");
        $events = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $events]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['title'], $data['start_date'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, start_date, end_date, location, type, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['start_date'],
            $data['end_date'] ?? null,
            $data['location'] ?? null,
            $data['type'] ?? 'Event',
            $data['image_url'] ?? null
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Event created successfully.', 'id' => $pdo->lastInsertId()]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
