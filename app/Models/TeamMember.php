<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class TeamMember
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
            'SELECT id, name, role, program_name, bio, photo_path, facebook_url, instagram_url,
                    is_active, display_order, created_at, updated_at
             FROM team_members
             ORDER BY display_order, name, id'
        );

        return $this->rows($statement->fetchAll());
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function active(): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, name, role, program_name, bio, photo_path, facebook_url, instagram_url, display_order
             FROM team_members
             WHERE is_active = :active
             ORDER BY display_order, name, id'
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
            'SELECT id, name, role, program_name, bio, photo_path, facebook_url, instagram_url, is_active, display_order
             FROM team_members
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
            'INSERT INTO team_members
                (name, role, program_name, bio, photo_path, facebook_url, instagram_url, is_active, display_order)
             VALUES
                (:name, :role, :program_name, :bio, :photo_path, :facebook_url,
                 :instagram_url, :is_active, :display_order)'
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
            'UPDATE team_members
             SET name = :name,
                 role = :role,
                 program_name = :program_name,
                 bio = :bio,
                 photo_path = :photo_path,
                 facebook_url = :facebook_url,
                 instagram_url = :instagram_url,
                 is_active = :is_active,
                 display_order = :display_order
             WHERE id = :id'
        );
        $statement->execute($payload);
    }

    public function toggle(int $id): void
    {
        $statement = $this->pdo()->prepare(
            'UPDATE team_members SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo()->prepare('DELETE FROM team_members WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function activeCount(): int
    {
        $statement = $this->pdo()->query('SELECT COUNT(*) AS total FROM team_members WHERE is_active = 1');
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
            'role' => (string) $data['role'],
            'program_name' => (string) $data['program_name'],
            'bio' => (string) $data['bio'],
            'photo_path' => (string) $data['photo_path'],
            'facebook_url' => (string) $data['facebook_url'],
            'instagram_url' => (string) $data['instagram_url'],
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
