<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\LoginRateLimiterService;
use PHPUnit\Framework\TestCase;

final class LoginRateLimiterServiceTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        $this->path = sys_get_temp_dir() . '/radio-rumbo-rate-limit-' . bin2hex(random_bytes(4));
        putenv('LOGIN_MAX_ATTEMPTS=2');
        putenv('LOGIN_WINDOW_SECONDS=900');
    }

    protected function tearDown(): void
    {
        putenv('LOGIN_MAX_ATTEMPTS');
        putenv('LOGIN_WINDOW_SECONDS');

        foreach (glob($this->path . '/*') ?: [] as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        if (is_dir($this->path)) {
            rmdir($this->path);
        }
    }

    public function testLimiterBlocksAfterConfiguredFailures(): void
    {
        $limiter = new LoginRateLimiterService($this->path);

        self::assertFalse($limiter->tooManyAttempts('admin@example.invalid', '127.0.0.1'));

        $limiter->recordFailure('admin@example.invalid', '127.0.0.1');
        $limiter->recordFailure('admin@example.invalid', '127.0.0.1');

        self::assertTrue($limiter->tooManyAttempts('admin@example.invalid', '127.0.0.1'));
    }

    public function testLimiterCanBeClearedAfterSuccessfulLogin(): void
    {
        $limiter = new LoginRateLimiterService($this->path);
        $limiter->recordFailure('admin@example.invalid', '127.0.0.1');
        $limiter->clear('admin@example.invalid', '127.0.0.1');

        self::assertFalse($limiter->tooManyAttempts('admin@example.invalid', '127.0.0.1'));
    }
}
