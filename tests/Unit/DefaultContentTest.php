<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class DefaultContentTest extends TestCase
{
    public function testDefaultContentHasExpectedStructure(): void
    {
        $data = require dirname(__DIR__, 2) . '/config/default-content.php';

        self::assertIsArray($data);
        self::assertArrayHasKey('current_program', $data);
        self::assertArrayHasKey('today_schedule', $data);
        self::assertArrayHasKey('news', $data);
        self::assertArrayHasKey('dashboard', $data);
        self::assertSame('Señal Radio Rumbo', $data['current_program']['name']);
        self::assertSame('91.1 FM', $data['station']['frequency_fm']);
        self::assertSame('1560 AM', $data['station']['frequency_am']);
    }
}
