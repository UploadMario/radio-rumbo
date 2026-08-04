<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Models\Role;

final class RoleMiddleware
{
    public const ROLE_SUPER_ADMIN = Role::SUPER_ADMIN;
    public const ROLE_ADMIN = Role::ADMIN;

    /**
     * @return list<string>
     */
    public static function allowedRoles(): array
    {
        return Role::allowed();
    }

    public function handle(string $role): bool
    {
        return in_array($role, self::allowedRoles(), true);
    }
}
