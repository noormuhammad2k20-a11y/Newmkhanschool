<?php
namespace App\Repositories;

use App\Models\Student;
use App\Core\Database;
use PDO;

class StudentRepository extends BaseRepository {
    protected string $modelClass = Student::class;

    /**
     * Search and filter student records.
     */
    public function search(array $filters, int $limit = 50, int $offset = 0): array {
        $db = Database::getConnection();

        $query = "
            SELECT s.*, c.name as class_name, sec.name as section_name 
            FROM `students` s 
            LEFT JOIN `classes` c ON s.current_class_id = c.id
            LEFT JOIN `sections` sec ON s.current_section_id = sec.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.admission_no LIKE ? OR s.b_form_number LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        }
        if (!empty($filters['class_id'])) {
            $query .= " AND s.current_class_id = ?";
            $params[] = $filters['class_id'];
        }
        if (!empty($filters['section_id'])) {
            $query .= " AND s.current_section_id = ?";
            $params[] = $filters['section_id'];
        }
        if (!empty($filters['status'])) {
            $query .= " AND s.status = ?";
            $params[] = ucfirst($filters['status']);
        }

        $query .= " ORDER BY s.id DESC LIMIT ? OFFSET ?";

        $stmt = $db->prepare($query);

        $paramIndex = 1;
        foreach ($params as $param) {
            $stmt->bindValue($paramIndex++, $param);
        }
        $stmt->bindValue($paramIndex++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, $offset, PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new Student($row), $rows);
    }
}
