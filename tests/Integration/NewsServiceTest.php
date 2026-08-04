<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\News;
use App\Services\NewsService;
use PDO;
use PHPUnit\Framework\TestCase;

final class NewsServiceTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            self::markTestSkipped('pdo_sqlite no está disponible.');
        }

        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec(
            'CREATE TABLE news (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                slug TEXT NOT NULL,
                summary TEXT NOT NULL,
                body TEXT NOT NULL,
                category TEXT NOT NULL,
                status TEXT NOT NULL,
                published_at TEXT NULL,
                created_by INTEGER NULL,
                updated_by INTEGER NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )'
        );
    }

    public function testCreatesValidNews(): void
    {
        $result = $this->service()->create([
            'title' => 'Últimas noticias de Huancayo',
            'summary' => 'Resumen de prueba',
            'body' => 'Contenido de prueba',
            'category' => 'Huancayo',
            'status' => 'published',
        ], 1);

        self::assertTrue($result['ok']);
        self::assertSame('ultimas-noticias-de-huancayo', $result['data']['slug']);
    }

    public function testRejectsNewsWithoutTitle(): void
    {
        $result = $this->service()->create([
            'summary' => 'Resumen de prueba',
            'body' => 'Contenido de prueba',
            'category' => 'Huancayo',
            'status' => 'draft',
        ], 1);

        self::assertFalse($result['ok']);
        self::assertArrayHasKey('title', $result['errors']);
    }

    public function testPublicQueryOnlyReturnsPublishedNews(): void
    {
        $service = $this->service();
        $service->create([
            'title' => 'Publicada',
            'summary' => 'Resumen',
            'body' => 'Contenido',
            'category' => 'Huancayo',
            'status' => 'published',
        ], 1);
        $service->create([
            'title' => 'Borrador',
            'summary' => 'Resumen',
            'body' => 'Contenido',
            'category' => 'Huancayo',
            'status' => 'draft',
        ], 1);

        $published = $service->published();

        self::assertCount(1, $published);
        self::assertSame('published', $published[0]['status']);
    }

    private function service(): NewsService
    {
        return new NewsService(new News($this->pdo));
    }
}
