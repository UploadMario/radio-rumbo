<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Middleware\RoleMiddleware;
use PHPUnit\Framework\TestCase;

final class RoleTest extends TestCase
{
    public function testOnlyAllowedRolesExist(): void
    {
        self::assertSame(
            ['Super Admin', 'Admin'],
            RoleMiddleware::allowedRoles()
        );
    }

    public function testRoleMiddlewareAcceptsOnlyAllowedRoles(): void
    {
        $middleware = new RoleMiddleware();

        self::assertTrue($middleware->handle('Super Admin'));
        self::assertTrue($middleware->handle('Admin'));
        self::assertFalse($middleware->handle('Editor'));
    }
}
