<?php
// api/teachers.php
require_once '../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $search = $_GET['search'] ?? '';
        
        $query = "SELECT * FROM teachers WHERE 1=1";
        $params = [];
        
        if ($search) {
            $query .= " AND (full_name LIKE ? OR employee_number LIKE ? OR specialization LIKE ?)";
            $searchTerm = "%$search%";
            array_push($params, $searchTerm, $searchTerm, $searchTerm);
        }
        
        $query .= " ORDER BY full_name ASC LIMIT 50";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $teachers = $stmt->fetchAll();
        
        echo json_encode(['status' => 'success', 'data' => $teachers]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['first_name'], $data['email'], $data['subject_specialization'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit;
    }
    
    try {
        $employee_no = 'EMP-' . rand(1000, 9999);
        $full_name = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));
        
        $stmt = $pdo->prepare("INSERT INTO teachers (employee_number, full_name, email, mobile, specialization) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $employee_no,
            $full_name,
            $data['email'],
            $data['phone'] ?? null,
            $data['subject_specialization']
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Teacher added successfully.', 'id' => $pdo->lastInsertId()]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

?>
