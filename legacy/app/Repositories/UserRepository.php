<?php
namespace App\Repositories;

use App\Models\User;
use App\Core\Database;

class UserRepository extends BaseRepository {
    protected string $modelClass = User::class;

    /**
     * Retrieve user by email address.
     */
    public function findByEmail(string $email): ?User {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `email` = ? LIMIT 1");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }
}
