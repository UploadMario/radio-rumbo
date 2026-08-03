<?php

declare(strict_types=1);

namespace App\Middleware;

final class RoleMiddleware
{
    public const ROLE_SUPER_ADMIN = 'Super Admin';
    public const ROLE_ADMIN = 'Admin';

    /**
     * @return list<string>
     */
    public static function allowedRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
        ];
    }

    public function handle(string $role): bool
    {
        return in_array($role, self::allowedRoles(), true);
    }
}
