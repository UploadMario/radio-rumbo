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
}
