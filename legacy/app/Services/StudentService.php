<?php
namespace App\Services;

use App\Repositories\StudentRepository;
use Exception;

class StudentService {
    private StudentRepository $studentRepository;

    public function __construct() {
        $this->studentRepository = new StudentRepository();
    }

    /**
     * Get a list of filtered students.
     */
    public function listStudents(array $filters, int $limit = 50, int $offset = 0): array {
        $students = $this->studentRepository->search($filters, $limit, $offset);
        return array_map(fn($student) => $student->toArray(), $students);
    }

    /**
     * Enroll a new student.
     */
    public function enrollStudent(array $data): array {
        if (empty($data['first_name']) || empty($data['admission_number']) || empty($data['date_of_birth'])) {
            throw new Exception("Missing required fields for student registration.");
        }

        $existing = $this->studentRepository->where('admission_no', $data['admission_number'], 1);
        if (!empty($existing)) {
            throw new Exception("Admission number '{$data['admission_number']}' is already in use.");
        }

        $attributes = [
            'admission_no' => $data['admission_number'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? '',
            'gender' => $data['gender'] ?? 'Other',
            'dob' => $data['date_of_birth'],
            'b_form_number' => $data['national_id'] ?? null,
            'father_name' => $data['guardian_name'] ?? null,
            'father_cnic' => $data['guardian_id'] ?? null,
            'mobile_number' => $data['emergency_contact'] ?? null,
            'current_class_id' => !empty($data['current_class_id']) ? $data['current_class_id'] : null,
            'current_section_id' => !empty($data['current_section_id']) ? $data['current_section_id'] : null,
            'status' => 'Regular'
        ];

        return $this->studentRepository->create($attributes)->toArray();
    }

    /**
     * Retrieve single student by ID.
     */
    public function getStudent(int $id): ?array {
        $student = $this->studentRepository->find($id);
        return $student ? $student->toArray() : null;
    }
}
