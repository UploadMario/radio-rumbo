<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class ContactMessage
{
    public function __construct(private ?PDO $connection = null)
    {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allForAdmin(?string $status = null): array
    {
        if ($status !== null && $status !== '') {
            $statement = $this->pdo()->prepare(
                'SELECT id, name, email, phone, subject, type, status, created_at
                 FROM contact_messages
                 WHERE status = :status
                 ORDER BY created_at DESC, id DESC'
            );
            $statement->execute(['status' => $status]);

            return $this->rows($statement->fetchAll());
        }

        $statement = $this->pdo()->query(
            'SELECT id, name, email, phone, subject, type, status, created_at
             FROM contact_messages
             ORDER BY created_at DESC, id DESC'
        );

        return $this->rows($statement->fetchAll());
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, name, email, phone, subject, message, type, status,
                    ip_address, user_agent, created_at, updated_at
             FROM contact_messages
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO contact_messages
                (name, email, phone, subject, message, type, status, ip_address, user_agent)
             VALUES
                (:name, :email, :phone, :subject, :message, :type, :status, :ip_address, :user_agent)'
        );
        $statement->execute($this->payload($data));

        return (int) $this->pdo()->lastInsertId();
    }

    public function markRead(int $id): void
    {
        $statement = $this->pdo()->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
        $statement->execute(['status' => 'read', 'id' => $id]);
    }

    public function archive(int $id): void
    {
        $statement = $this->pdo()->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
        $statement->execute(['status' => 'archived', 'id' => $id]);
    }

    public function newCount(): int
    {
        $statement = $this->pdo()->query("SELECT COUNT(*) AS total FROM contact_messages WHERE status = 'new'");
        $row = $statement->fetch();

        return is_array($row) ? (int) $row['total'] : 0;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function payload(array $data): array
    {
        return [
            'name' => (string) $data['name'],
            'email' => (string) $data['email'],
            'phone' => (string) $data['phone'],
            'subject' => (string) $data['subject'],
            'message' => (string) $data['message'],
            'type' => (string) $data['type'],
            'status' => (string) $data['status'],
            'ip_address' => (string) $data['ip_address'],
            'user_agent' => (string) $data['user_agent'],
        ];
    }

    /**
     * @param array<int, mixed> $rows
     * @return list<array<string, mixed>>
     */
    private function rows(array $rows): array
    {
        $result = [];

        foreach ($rows as $row) {
            if (is_array($row)) {
                $result[] = $row;
            }
        }

        return $result;
    }

    private function pdo(): PDO
    {
        return $this->connection ?? Database::connection();
    }
}
