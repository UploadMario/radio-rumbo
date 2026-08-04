<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\TeamMember;
use App\Services\TeamService;
use PDO;
use PHPUnit\Framework\TestCase;

final class TeamServiceTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            self::markTestSkipped('pdo_sqlite no esta disponible.');
        }

        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec(
            'CREATE TABLE team_members (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                role TEXT NOT NULL,
                program_name TEXT NOT NULL,
                bio TEXT NOT NULL,
                photo_path TEXT NOT NULL,
                facebook_url TEXT NOT NULL,
                instagram_url TEXT NOT NULL,
                is_active INTEGER NOT NULL,
                display_order INTEGER NOT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )'
        );
    }

    public function testListsOnlyActiveMembers(): void
    {
        $service = new TeamService(new TeamMember($this->pdo));
        $service->create(['name' => 'Conductor principal', 'role' => 'Conduccion', 'is_active' => '1']);
        $service->create(['name' => 'Archivado', 'role' => 'Apoyo', 'is_active' => '0']);

        self::assertCount(1, $service->active());
    }
}
