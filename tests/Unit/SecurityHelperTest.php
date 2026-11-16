<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\SecurityHelper;
use PHPUnit\Framework\TestCase;

final class SecurityHelperTest extends TestCase
{
    public function testEscapesHtmlOutput(): void
    {
        $input = '<script>alert("xss")</script>';

        self::assertSame(
            '&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;',
            SecurityHelper::e($input)
        );
    }

    public function testCsrfTokenIsGeneratedAndVerified(): void
    {
        $previousPath = session_save_path();
        $previousId = session_id();
        $wasActive = session_status() === PHP_SESSION_ACTIVE;
        $sessionPath = sys_get_temp_dir() . '/radio-rumbo-csrf-' . bin2hex(random_bytes(4));

        if ($wasActive) {
            session_write_close();
        }

        try {
            mkdir($sessionPath, 0700, true);
            session_save_path($sessionPath);
            session_id('csrf-' . bin2hex(random_bytes(8)));

            $token = SecurityHelper::csrfToken();

            self::assertNotSame('', $token);
            self::assertTrue(SecurityHelper::verifyCsrfToken($token));
            self::assertFalse(SecurityHelper::verifyCsrfToken('invalid-token'));
            self::assertFalse(SecurityHelper::verifyCsrfToken(''));
        } finally {
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
            }

            session_save_path($previousPath);
            session_id($previousId);
            if ($wasActive) {
                session_start();
            }

            foreach (glob($sessionPath . '/*') ?: [] as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            if (is_dir($sessionPath)) {
                rmdir($sessionPath);
            }
        }
    }
}
