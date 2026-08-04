<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validators\LoginValidator;
use PHPUnit\Framework\TestCase;

final class LoginValidatorTest extends TestCase
{
    public function testRequiresIdentifierAndPassword(): void
    {
        $errors = (new LoginValidator())->validate([]);

        self::assertArrayHasKey('identifier', $errors);
        self::assertArrayHasKey('password', $errors);
    }

    public function testAcceptsBasicCredentialsShape(): void
    {
        $errors = (new LoginValidator())->validate([
            'identifier' => 'Admin',
            'password' => 'secret',
        ]);

        self::assertSame([], $errors);
    }
}
