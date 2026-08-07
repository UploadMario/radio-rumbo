<?php

declare(strict_types=1);

use App\Core\View;
use App\Services\BannerService;
use App\Services\SettingService;
use App\Services\UrgentNoticeService;
use Dotenv\Dotenv;

function rr_bootstrap(): string
{
    $rootPath = dirname(__DIR__);
    $autoloadPath = $rootPath . '/vendor/autoload.php';

    if (is_file($autoloadPath)) {
        require_once $autoloadPath;
    }

    if (class_exists(Dotenv::class) && is_file($rootPath . '/.env')) {
        Dotenv::createImmutable($rootPath)->safeLoad();
    }

    return $rootPath;
}

/**
 * @return array<string, mixed>
 */
function rr_mock_data(string $rootPath): array
{
    $data = require $rootPath . '/config/mock-data.php';

    $mock = is_array($data) ? $data : [];
    $mock['station'] = (new SettingService())->station($mock);
    $mock['active_banners'] = rr_active_banners();
    $mock['urgent_notices'] = rr_active_notices();

    return $mock;
}

/** @return list<array<string, mixed>> */
function rr_active_banners(): array
{
    try {
        return (new BannerService())->active();
    } catch (Throwable) {
        return [];
    }
}

/** @return list<array<string, mixed>> */
function rr_active_notices(): array
{
    try {
        return (new UrgentNoticeService())->active();
    } catch (Throwable) {
        return [];
    }
}

function rr_render_public(string $page, string $title, string $active): void
{
    $rootPath = rr_bootstrap();

    View::render('layouts.public', [
        'active' => $active,
        'contentView' => $page,
        'mock' => rr_mock_data($rootPath),
        'pageTitle' => $title,
    ]);
}

/**
 * @param array<string, mixed> $extra
 */
function rr_render_admin(string $page, string $title, string $active = 'dashboard', array $extra = []): void
{
    $rootPath = rr_bootstrap();

    View::render('layouts.admin', array_merge([
        'active' => $active,
        'contentView' => $page,
        'mock' => rr_mock_data($rootPath),
        'pageTitle' => $title,
    ], $extra));
}
