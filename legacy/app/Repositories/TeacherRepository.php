<?php
namespace App\Repositories;

use App\Models\Teacher;
use App\Core\Database;
use PDO;

class TeacherRepository extends BaseRepository {
    protected string $modelClass = Teacher::class;

    /**
     * Search and filter teacher records.
     */
    public function search(array $filters, int $limit = 50, int $offset = 0): array {
        $db = Database::getConnection();

        $query = "SELECT * FROM `teachers` WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND (full_name LIKE ? OR employee_number LIKE ? OR specialization LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            array_push($params, $searchTerm, $searchTerm, $searchTerm);
        }

        $query .= " ORDER BY full_name ASC LIMIT ? OFFSET ?";

        $stmt = $db->prepare($query);

        $paramIndex = 1;
        foreach ($params as $param) {
            $stmt->bindValue($paramIndex++, $param);
        }
        $stmt->bindValue($paramIndex++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, $offset, PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new Teacher($row), $rows);
    }
}
