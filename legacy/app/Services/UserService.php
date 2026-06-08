<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Exception;

class UserService {
    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    /**
     * Authenticate user credentials.
     */
    public function authenticate(string $email, string $password): ?array {
        $user = $this->userRepository->findByEmail($email);
        if ($user && password_verify($password, $user->password_hash)) {
            if ($user->status !== 'active') {
                return null;
            }
            return $user->toArray();
        }
        return null;
    }

    /**
     * Register a new user with password hashing.
     */
    public function register(array $data): array {
        if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['role_id'])) {
            throw new Exception("Missing required fields for user registration.");
        }

        if ($this->userRepository->findByEmail($data['email'])) {
            throw new Exception("A user with this email address already exists.");
        }

        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role_id' => $data['role_id'],
            'school_id' => $data['school_id'] ?? null,
            'status' => 'active'
        ];

        return $this->userRepository->create($attributes)->toArray();
    }
}
