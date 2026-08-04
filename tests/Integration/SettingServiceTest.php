<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\Setting;
use App\Services\SettingService;
use PDO;
use PHPUnit\Framework\TestCase;

final class SettingServiceTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            self::markTestSkipped('pdo_sqlite no está disponible.');
        }

        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('CREATE TABLE settings (setting_key TEXT NOT NULL, setting_value TEXT NULL)');
    }

    public function testReadsDatabaseValueBeforeFallback(): void
    {
        $this->pdo->exec(
            "INSERT INTO settings (setting_key, setting_value)
             VALUES ('stream_audio_url', 'https://mediastreamm.com/8008/stream;')"
        );

        $station = (new SettingService(new Setting($this->pdo)))->station(['station' => []]);

        self::assertSame('https://mediastreamm.com/8008/stream;', $station['stream_audio_url']);
    }

    public function testRejectsUnsafeUrlAndUsesEmptyValue(): void
    {
        $this->pdo->exec(
            "INSERT INTO settings (setting_key, setting_value)
             VALUES ('live_video_widget_url', 'javascript:alert(1)')"
        );

        $station = (new SettingService(new Setting($this->pdo)))->station(['station' => []]);

        self::assertSame('', $station['live_video_widget_url']);
    }

    public function testUsesFallbackWhenDatabaseAndEnvAreEmpty(): void
    {
        $station = (new SettingService(new Setting($this->pdo)))->station([
            'station' => [
                'facebook_page_url' => 'https://www.facebook.com/radiorumboperu',
            ],
        ]);

        self::assertSame('https://www.facebook.com/radiorumboperu', $station['facebook_page_url']);
    }
}
