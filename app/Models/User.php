<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class User
{
    public function __construct(private ?PDO $connection = null)
    {
    }

    /**
     * @return array{id:int,name:string,email:string,password_hash:string,role:string}|null
     */
    public function findActiveByIdentifier(string $identifier): ?array
    {
        $sql = 'SELECT users.id, users.name, users.email, users.password_hash, roles.name AS role
                FROM users
                INNER JOIN roles ON roles.id = users.role_id
                WHERE users.status = :status
                  AND (users.email = :email_identifier OR users.name = :name_identifier)
                LIMIT 1';

        $statement = $this->pdo()->prepare($sql);
        $statement->execute([
            'status' => 'active',
            'email_identifier' => $identifier,
            'name_identifier' => $identifier,
        ]);

        $row = $statement->fetch();

        if (!is_array($row)) {
            return null;
        }

        return [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'email' => (string) $row['email'],
            'password_hash' => (string) $row['password_hash'],
            'role' => (string) $row['role'],
        ];
    }

    public function touchLastLogin(int $userId): void
    {
        $statement = $this->pdo()->prepare('UPDATE users SET last_login_at = CURRENT_TIMESTAMP WHERE id = :id');
        $statement->execute(['id' => $userId]);
    }

    private function pdo(): PDO
    {
        return $this->connection ?? Database::connection();
    }
}
