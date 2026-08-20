<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class UrgentNotice
{
    public function __construct(private ?PDO $connection = null)
    {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allForAdmin(): array
    {
        $statement = $this->pdo()->query(
            'SELECT id, title, message, type, status, start_at, end_at, display_order
             FROM urgent_notices
             ORDER BY display_order, id DESC'
        );

        return $this->rows($statement->fetchAll());
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function active(?string $now = null): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, title, message, type, status, start_at, end_at, display_order
             FROM urgent_notices
             WHERE status = :status
               AND (start_at IS NULL OR start_at <= :now)
               AND (end_at IS NULL OR end_at >= :now)
             ORDER BY display_order, id DESC'
        );
        $statement->execute(['status' => 'active', 'now' => $now ?? date('Y-m-d H:i:s')]);

        return $this->rows($statement->fetchAll());
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, title, message, type, status, start_at, end_at, display_order
             FROM urgent_notices
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
            'INSERT INTO urgent_notices (title, message, type, status, start_at, end_at, display_order)
             VALUES (:title, :message, :type, :status, :start_at, :end_at, :display_order)'
        );
        $statement->execute($this->payload($data));

        return (int) $this->pdo()->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $payload = $this->payload($data);
        $payload['id'] = $id;
        $statement = $this->pdo()->prepare(
            'UPDATE urgent_notices
             SET title = :title, message = :message, type = :type, status = :status,
                 start_at = :start_at, end_at = :end_at, display_order = :display_order
             WHERE id = :id'
        );
        $statement->execute($payload);
    }

    public function toggle(int $id): void
    {
        $statement = $this->pdo()->prepare(
            'UPDATE urgent_notices SET status = CASE WHEN status = "active" THEN "inactive" ELSE "active" END
             WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
    }

    public function archive(int $id): void
    {
        $statement = $this->pdo()->prepare('UPDATE urgent_notices SET status = :status WHERE id = :id');
        $statement->execute(['status' => 'archived', 'id' => $id]);
    }

    public function activeCount(): int
    {
        $statement = $this->pdo()->query("SELECT COUNT(*) AS total FROM urgent_notices WHERE status = 'active'");
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
            'title' => (string) $data['title'],
            'message' => (string) $data['message'],
            'type' => (string) $data['type'],
            'status' => (string) $data['status'],
            'start_at' => $data['start_at'],
            'end_at' => $data['end_at'],
            'display_order' => (int) $data['display_order'],
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
