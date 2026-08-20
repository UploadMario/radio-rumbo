<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\LoggerService;
use PHPUnit\Framework\TestCase;

final class LoggerServiceTest extends TestCase
{
    public function testLoggerSanitizesSensitiveContext(): void
    {
        $logger = new LoggerService();
        $context = $logger->sanitize([
            'password' => 'secret',
            'nested' => ['_csrf_token' => 'token'],
            'safe' => 'value',
        ]);

        self::assertSame('[redacted]', $context['password']);
        self::assertSame('[redacted]', $context['nested']['_csrf_token']);
        self::assertSame('value', $context['safe']);
    }
}
