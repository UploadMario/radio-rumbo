<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Program
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
            'SELECT id, name, slug, description, host_name, day_of_week, start_time, end_time,
                    category, is_active, display_order, created_at, updated_at
             FROM programs
             ORDER BY CASE day_of_week
                          WHEN "monday" THEN 1 WHEN "tuesday" THEN 2 WHEN "wednesday" THEN 3
                          WHEN "thursday" THEN 4 WHEN "friday" THEN 5 WHEN "saturday" THEN 6
                          WHEN "sunday" THEN 7 ELSE 8
                      END,
                      start_time, display_order, id'
        );

        return $this->rows($statement->fetchAll());
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function active(): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, name, slug, description, host_name, day_of_week, start_time, end_time,
                    category, is_active, display_order
             FROM programs
             WHERE is_active = :active
             ORDER BY CASE day_of_week
                          WHEN "monday" THEN 1 WHEN "tuesday" THEN 2 WHEN "wednesday" THEN 3
                          WHEN "thursday" THEN 4 WHEN "friday" THEN 5 WHEN "saturday" THEN 6
                          WHEN "sunday" THEN 7 ELSE 8
                      END,
                      start_time, display_order, id'
        );
        $statement->execute(['active' => 1]);

        return $this->rows($statement->fetchAll());
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, name, slug, description, host_name, day_of_week, start_time, end_time,
                    category, is_active, display_order
             FROM programs
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
            'INSERT INTO programs
                (name, slug, description, host_name, day_of_week, start_time,
                 end_time, category, is_active, display_order)
             VALUES
                (:name, :slug, :description, :host_name, :day_of_week, :start_time,
                 :end_time, :category, :is_active, :display_order)'
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
            'UPDATE programs
             SET name = :name,
                 slug = :slug,
                 description = :description,
                 host_name = :host_name,
                 day_of_week = :day_of_week,
                 start_time = :start_time,
                 end_time = :end_time,
                 category = :category,
                 is_active = :is_active,
                 display_order = :display_order
             WHERE id = :id'
        );
        $statement->execute($payload);
    }

    public function toggle(int $id): void
    {
        $statement = $this->pdo()->prepare(
            'UPDATE programs SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
    }

    public function archive(int $id): void
    {
        $statement = $this->pdo()->prepare('UPDATE programs SET is_active = 0 WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    /**
     * Backward-compatible alias. Program records are never physically deleted
     * through the administrative flow; they are archived by deactivation.
     */
    public function delete(int $id): void
    {
        $this->archive($id);
    }

    public function activeCount(): int
    {
        $statement = $this->pdo()->query('SELECT COUNT(*) AS total FROM programs WHERE is_active = 1');
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
            'slug' => (string) $data['slug'],
            'description' => (string) $data['description'],
            'host_name' => (string) $data['host_name'],
            'day_of_week' => (string) $data['day_of_week'],
            'start_time' => (string) $data['start_time'],
            'end_time' => (string) $data['end_time'],
            'category' => (string) $data['category'],
            'is_active' => (int) $data['is_active'],
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
