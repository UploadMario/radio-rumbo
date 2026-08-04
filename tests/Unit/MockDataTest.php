<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class MockDataTest extends TestCase
{
    public function testMockDataHasExpectedPhaseOneStructure(): void
    {
        $data = require dirname(__DIR__, 2) . '/config/mock-data.php';

        self::assertIsArray($data);
        self::assertArrayHasKey('current_program', $data);
        self::assertArrayHasKey('today_schedule', $data);
        self::assertArrayHasKey('news', $data);
        self::assertArrayHasKey('dashboard', $data);
        self::assertSame('Programa Matinal', $data['current_program']['name']);
    }
}
