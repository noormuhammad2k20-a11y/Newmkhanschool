<?php
namespace App\Services;

use App\Repositories\TeacherRepository;
use Exception;

class TeacherService {
    private TeacherRepository $teacherRepository;

    public function __construct() {
        $this->teacherRepository = new TeacherRepository();
    }

    /**
     * Search and list teachers.
     */
    public function listTeachers(array $filters, int $limit = 50, int $offset = 0): array {
        $teachers = $this->teacherRepository->search($filters, $limit, $offset);
        return array_map(fn($teacher) => $teacher->toArray(), $teachers);
    }

    /**
     * Register a new teacher.
     */
    public function registerTeacher(array $data): array {
        if (empty($data['first_name']) || empty($data['email']) || empty($data['subject_specialization'])) {
            throw new Exception("Missing required fields for teacher registration.");
        }

        $existing = $this->teacherRepository->where('email', $data['email'], 1);
        if (!empty($existing)) {
            throw new Exception("A teacher with email '{$data['email']}' is already registered.");
        }

        $employeeNumber = 'EMP-' . rand(1000, 9999);
        $fullName = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));

        $attributes = [
            'employee_number' => $employeeNumber,
            'full_name' => $fullName,
            'email' => $data['email'],
            'mobile' => $data['phone'] ?? null,
            'specialization' => $data['subject_specialization'],
            'cnic' => $data['cnic'] ?? null,
            'qualification' => $data['qualification'] ?? null,
            'experience' => $data['experience'] ?? 0
        ];

        return $this->teacherRepository->create($attributes)->toArray();
    }

    /**
     * Retrieve single teacher by ID.
     */
    public function getTeacher(int $id): ?array {
        $teacher = $this->teacherRepository->find($id);
        return $teacher ? $teacher->toArray() : null;
    }
}
