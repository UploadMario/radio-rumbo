<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validators\UrgentNoticeValidator;
use PHPUnit\Framework\TestCase;

final class UrgentNoticeValidatorTest extends TestCase
{
    public function testAcceptsValidNotice(): void
    {
        $errors = (new UrgentNoticeValidator())->validate([
            'title' => 'Aviso importante',
            'message' => 'Programacion especial durante la transmision.',
            'type' => 'info',
            'status' => 'active',
        ]);

        self::assertSame([], $errors);
    }

    public function testRejectsInvalidType(): void
    {
        $errors = (new UrgentNoticeValidator())->validate([
            'title' => 'Aviso importante',
            'message' => 'Programacion especial durante la transmision.',
            'type' => 'danger',
            'status' => 'active',
        ]);

        self::assertArrayHasKey('type', $errors);
    }
}
