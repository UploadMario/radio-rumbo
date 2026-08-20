<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\SessionService;
use PHPUnit\Framework\TestCase;

final class SessionServiceTest extends TestCase
{
    public function testSessionExpiresAfterConfiguredInactivity(): void
    {
        self::assertTrue(SessionService::isExpired(100, 3701, 3600));
    }

    public function testSessionDoesNotExpireWithinValidWindow(): void
    {
        self::assertFalse(SessionService::isExpired(100, 3699, 3600));
    }

    public function testSessionDoesNotExpireWhenClockMovesBackwards(): void
    {
        self::assertFalse(SessionService::isExpired(500, 100, 3600));
    }
}
