<?php

declare(strict_types=1);

namespace App\Helpers;

use DateTimeZone;

final class EnvironmentHelper
{
    public static function string(string $key, string $default = ''): string
    {
        foreach ([getenv($key), $_ENV[$key] ?? null, $_SERVER[$key] ?? null] as $value) {
            if (!is_string($value)) {
                continue;
            }

            $value = trim($value);
            if ($value !== '') {
                return $value;
            }
        }

        return $default;
    }

    public static function int(string $key, int $default, int $minimum = 0): int
    {
        $value = self::string($key);
        if ($value === '' || !ctype_digit($value)) {
            return $default;
        }

        return max($minimum, (int) $value);
    }

    public static function timezone(): string
    {
        $timezone = self::string('APP_TIMEZONE', 'America/Lima');

        return in_array($timezone, DateTimeZone::listIdentifiers(), true) ? $timezone : 'America/Lima';
    }

    public static function applyTimezone(): void
    {
        date_default_timezone_set(self::timezone());
    }
}
