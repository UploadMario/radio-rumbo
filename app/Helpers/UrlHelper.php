<?php

declare(strict_types=1);

namespace App\Helpers;

final class UrlHelper
{
    public static function httpsOrEmpty(?string $url): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return '';
        }

        $parts = parse_url($url);

        if (!is_array($parts) || ($parts['scheme'] ?? '') !== 'https' || empty($parts['host'])) {
            return '';
        }

        return $url;
    }

    public static function isHttps(string $url): bool
    {
        return self::httpsOrEmpty($url) !== '';
    }
}
