<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Setting
{
    public function __construct(private ?PDO $connection = null)
    {
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        $statement = $this->pdo()->query('SELECT setting_key, setting_value FROM settings');
        $settings = [];

        foreach ($statement->fetchAll() as $row) {
            if (is_array($row)) {
                $settings[(string) $row['setting_key']] = (string) ($row['setting_value'] ?? '');
            }
        }

        return $settings;
    }

    /**
     * @param array<string, string> $settings
     */
    public function upsertMany(array $settings): void
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO settings (setting_key, setting_value, description)
             VALUES (:setting_key, :setting_value, :description)
             ON DUPLICATE KEY UPDATE
                setting_value = VALUES(setting_value),
                description = VALUES(description)'
        );

        foreach ($settings as $key => $value) {
            $statement->execute([
                'setting_key' => $key,
                'setting_value' => $value,
                'description' => 'Editable desde panel administrativo.',
            ]);
        }
    }

    private function pdo(): PDO
    {
        return $this->connection ?? Database::connection();
    }
}
