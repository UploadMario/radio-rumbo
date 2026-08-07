<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\Banner;
use App\Services\BannerService;
use PDO;
use PHPUnit\Framework\TestCase;

final class BannerServiceTest extends TestCase
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
            'CREATE TABLE banners (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT, subtitle TEXT, image_path TEXT, link_url TEXT, position TEXT, status TEXT,
                start_at TEXT NULL, end_at TEXT NULL, display_order INTEGER
            )'
        );
    }

    public function testListsActiveBannersOnly(): void
    {
        $service = new BannerService(new Banner($this->pdo));
        $service->create(['title' => 'Activo', 'position' => 'home_middle', 'status' => 'active']);
        $service->create(['title' => 'Borrador', 'position' => 'home_middle', 'status' => 'draft']);

        self::assertCount(1, $service->active());
    }
}
