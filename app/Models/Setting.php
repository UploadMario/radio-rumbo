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

    private function pdo(): PDO
    {
        return $this->connection ?? Database::connection();
    }
}
