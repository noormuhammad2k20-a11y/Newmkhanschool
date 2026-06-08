<?php
// api/students.php
require_once '../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $search = $_GET['search'] ?? '';
        $class_id = $_GET['class_id'] ?? '';
        $section_id = $_GET['section_id'] ?? '';
        $status = $_GET['status'] ?? '';
        
        $query = "
            SELECT s.*, c.name as class_name, sec.name as section_name 
            FROM students s 
            LEFT JOIN classes c ON s.current_class_id = c.id
            LEFT JOIN sections sec ON s.current_section_id = sec.id
            WHERE 1=1
        ";
        $params = [];
        
        if ($search) {
            $query .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.admission_no LIKE ? OR s.b_form_number LIKE ?)";
            $searchTerm = "%$search%";
            array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        }
        if ($class_id) {
            $query .= " AND s.current_class_id = ?";
            $params[] = $class_id;
        }
        if ($section_id) {
            $query .= " AND s.current_section_id = ?";
            $params[] = $section_id;
        }
        if ($status) {
            $query .= " AND s.status = ?";
            $params[] = ucfirst($status);
        }
        
        $query .= " ORDER BY s.id DESC LIMIT 50";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $students = $stmt->fetchAll();
        
        echo json_encode(['status' => 'success', 'data' => $students]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['first_name'], $data['admission_number'], $data['date_of_birth'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO students (admission_no, first_name, last_name, gender, dob, b_form_number, father_name, father_cnic, mobile_number, current_class_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['admission_number'],
            $data['first_name'],
            $data['last_name'] ?? '',
            $data['gender'] ?? 'Other',
            $data['date_of_birth'],
            $data['national_id'] ?? null,
            $data['guardian_name'] ?? null,
            $data['guardian_id'] ?? null,
            $data['emergency_contact'] ?? null,
            $data['current_class_id'] ?: null
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Student created successfully.', 'id' => $pdo->lastInsertId()]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

?>
