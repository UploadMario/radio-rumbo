<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\UrlHelper;
use PHPUnit\Framework\TestCase;

final class UrlHelperTest extends TestCase
{
    public function testAcceptsHttpsUrl(): void
    {
        self::assertSame(
            'https://mediastreamm.com/8008/stream;',
            UrlHelper::httpsOrEmpty('https://mediastreamm.com/8008/stream;')
        );
    }

    public function testRejectsUnsafeProtocol(): void
    {
        self::assertSame('', UrlHelper::httpsOrEmpty('javascript:alert(1)'));
        self::assertFalse(UrlHelper::isHttps('http://example.com'));
    }
}
