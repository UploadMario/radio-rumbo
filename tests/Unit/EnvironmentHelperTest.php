<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\EnvironmentHelper;
use PHPUnit\Framework\TestCase;

final class EnvironmentHelperTest extends TestCase
{
    protected function tearDown(): void
    {
        putenv('APP_TIMEZONE');
        unset($_ENV['APP_TIMEZONE'], $_SERVER['APP_TIMEZONE']);
    }

    public function testTimezoneUsesAmericaLimaFallback(): void
    {
        self::assertSame('America/Lima', EnvironmentHelper::timezone());
    }

    public function testTimezoneRejectsInvalidValue(): void
    {
        putenv('APP_TIMEZONE=Invalid/Zone');

        self::assertSame('America/Lima', EnvironmentHelper::timezone());
    }

    public function testTimezoneReadsValidValue(): void
    {
        putenv('APP_TIMEZONE=America/Lima');

        self::assertSame('America/Lima', EnvironmentHelper::timezone());
    }
}
