<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class News
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
            'SELECT id, title, slug, summary, body, category, status, published_at, created_at, updated_at
             FROM news
             ORDER BY created_at DESC, id DESC'
        );

        return $this->rows($statement->fetchAll());
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function published(): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, title, slug, summary, body, category, status, published_at, created_at, updated_at
             FROM news
             WHERE status = :status
             ORDER BY COALESCE(published_at, created_at) DESC, id DESC'
        );
        $statement->execute(['status' => 'published']);

        return $this->rows($statement->fetchAll());
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, title, slug, summary, body, category, status, published_at, created_at, updated_at
             FROM news
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
            'INSERT INTO news (title, slug, summary, body, category, status, published_at, created_by, updated_by)
             VALUES (:title, :slug, :summary, :body, :category, :status, :published_at, :created_by, :updated_by)'
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
        unset($payload['created_by']);

        $statement = $this->pdo()->prepare(
            'UPDATE news
             SET title = :title,
                 slug = :slug,
                 summary = :summary,
                 body = :body,
                 category = :category,
                 status = :status,
                 published_at = :published_at,
                 updated_by = :updated_by
             WHERE id = :id'
        );
        $statement->execute($payload);
    }

    public function archive(int $id, int $userId): void
    {
        $statement = $this->pdo()->prepare(
            'UPDATE news SET status = :status, updated_by = :updated_by WHERE id = :id'
        );
        $statement->execute([
            'status' => 'archived',
            'updated_by' => $userId,
            'id' => $id,
        ]);
    }

    /**
     * @return array{total:int,published:int,draft:int}
     */
    public function counts(): array
    {
        $statement = $this->pdo()->query(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) AS published,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS draft
             FROM news"
        );
        $row = $statement->fetch();

        if (!is_array($row)) {
            return ['total' => 0, 'published' => 0, 'draft' => 0];
        }

        return [
            'total' => (int) $row['total'],
            'published' => (int) $row['published'],
            'draft' => (int) $row['draft'],
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function payload(array $data): array
    {
        return [
            'title' => (string) $data['title'],
            'slug' => (string) $data['slug'],
            'summary' => (string) $data['summary'],
            'body' => (string) $data['body'],
            'category' => (string) $data['category'],
            'status' => (string) $data['status'],
            'published_at' => $data['published_at'],
            'created_by' => $data['user_id'],
            'updated_by' => $data['user_id'],
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
