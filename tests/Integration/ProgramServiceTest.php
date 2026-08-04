<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\Program;
use App\Services\ProgramService;
use PDO;
use PHPUnit\Framework\TestCase;

final class ProgramServiceTest extends TestCase
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
            'CREATE TABLE programs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT NOT NULL,
                description TEXT NOT NULL,
                host_name TEXT NOT NULL,
                day_of_week TEXT NOT NULL,
                start_time TEXT NOT NULL,
                end_time TEXT NOT NULL,
                category TEXT NOT NULL,
                is_active INTEGER NOT NULL,
                display_order INTEGER NOT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )'
        );
    }

    public function testCreatesValidProgram(): void
    {
        $result = $this->service()->create([
            'name' => 'Programa matinal',
            'day_of_week' => 'monday',
            'start_time' => '06:00',
            'end_time' => '09:00',
            'is_active' => '1',
        ]);

        self::assertTrue($result['ok']);
        self::assertSame('programa-matinal', $result['data']['slug']);
    }

    public function testRejectsInvalidTimeRange(): void
    {
        $result = $this->service()->create([
            'name' => 'Programa matinal',
            'day_of_week' => 'monday',
            'start_time' => '09:00',
            'end_time' => '06:00',
        ]);

        self::assertFalse($result['ok']);
        self::assertArrayHasKey('end_time', $result['errors']);
    }

    public function testListsOnlyActivePrograms(): void
    {
        $service = $this->service();
        $service->create([
            'name' => 'Activo',
            'day_of_week' => 'monday',
            'start_time' => '06:00',
            'end_time' => '09:00',
            'is_active' => '1',
        ]);
        $service->create([
            'name' => 'Inactivo',
            'day_of_week' => 'monday',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'is_active' => '0',
        ]);

        self::assertCount(1, $service->active());
    }

    private function service(): ProgramService
    {
        return new ProgramService(new Program($this->pdo));
    }
}
