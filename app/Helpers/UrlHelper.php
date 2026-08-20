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

    public static function facebookPagePluginUrl(string $pageUrl): string
    {
        $pageUrl = self::httpsOrEmpty($pageUrl);
        if ($pageUrl === '') {
            return '';
        }

        return 'https://www.facebook.com/plugins/page.php?'
            . http_build_query([
                'href' => $pageUrl,
                'tabs' => 'timeline',
                'width' => '500',
                'height' => '650',
                'small_header' => 'false',
                'adapt_container_width' => 'true',
                'hide_cover' => 'false',
                'show_facepile' => 'true',
            ], '', '&', PHP_QUERY_RFC3986);
    }
}
