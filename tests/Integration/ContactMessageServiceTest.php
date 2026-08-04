<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\ContactMessage;
use App\Services\ContactMessageService;
use PDO;
use PHPUnit\Framework\TestCase;

final class ContactMessageServiceTest extends TestCase
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
            'CREATE TABLE contact_messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                phone TEXT NOT NULL,
                subject TEXT NOT NULL,
                message TEXT NOT NULL,
                type TEXT NOT NULL,
                status TEXT NOT NULL,
                ip_address TEXT NOT NULL,
                user_agent TEXT NOT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )'
        );
    }

    public function testCreatesValidContactMessage(): void
    {
        $result = $this->service()->create([
            'name' => 'Visitante',
            'email' => 'visitante@example.test',
            'subject' => 'Publicidad',
            'message' => 'Necesito informacion.',
            'type' => 'advertising',
        ]);

        self::assertTrue($result['ok']);
        self::assertSame(1, $this->service()->newCount());
    }

    public function testArchivesMessage(): void
    {
        $service = $this->service();
        $result = $service->create([
            'name' => 'Visitante',
            'subject' => 'Publicidad',
            'message' => 'Necesito informacion.',
            'type' => 'advertising',
        ]);

        $service->archive((int) $result['id']);

        self::assertSame(0, $service->newCount());
    }

    private function service(): ContactMessageService
    {
        return new ContactMessageService(new ContactMessage($this->pdo));
    }
}
