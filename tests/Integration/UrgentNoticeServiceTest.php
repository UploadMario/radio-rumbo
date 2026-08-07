<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\UrgentNotice;
use App\Services\UrgentNoticeService;
use PDO;
use PHPUnit\Framework\TestCase;

final class UrgentNoticeServiceTest extends TestCase
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
            'CREATE TABLE urgent_notices (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT, message TEXT, type TEXT, status TEXT,
                start_at TEXT NULL, end_at TEXT NULL, display_order INTEGER
            )'
        );
    }

    public function testListsActiveNoticesOnly(): void
    {
        $service = new UrgentNoticeService(new UrgentNotice($this->pdo));
        $service->create(['title' => 'Activo', 'message' => 'Mensaje', 'type' => 'info', 'status' => 'active']);
        $service->create(['title' => 'Borrador', 'message' => 'Mensaje', 'type' => 'info', 'status' => 'draft']);

        self::assertCount(1, $service->active());
    }
}
