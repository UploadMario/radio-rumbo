<?php

declare(strict_types=1);

namespace App\Models;

final class Role
{
    public const SUPER_ADMIN = 'Super Admin';
    public const ADMIN = 'Admin';

    /**
     * @return list<string>
     */
    public static function allowed(): array
    {
        return [self::SUPER_ADMIN, self::ADMIN];
    }
}
