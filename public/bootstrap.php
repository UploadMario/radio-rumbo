<?php

declare(strict_types=1);

use App\Core\View;
use App\Helpers\EnvironmentHelper;
use App\Services\BannerService;
use App\Services\LoggerService;
use App\Services\ProgramScheduleService;
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

    EnvironmentHelper::applyTimezone();

    return $rootPath;
}

/**
 * @return array<string, mixed>
 */
function rr_default_content(string $rootPath): array
{
    $data = require $rootPath . '/config/default-content.php';

    $content = is_array($data) ? $data : [];
    $content['station'] = (new SettingService())->station($content);
    $content['active_banners'] = rr_active_banners();
    $content['urgent_notices'] = rr_active_notices();
    $content = (new ProgramScheduleService())->applyContext($content, EnvironmentHelper::timezone());

    return $content;
}

/**
 * Alias temporal para entry points existentes; usar rr_default_content() en codigo nuevo.
 *
 * @return array<string, mixed>
 */
function rr_mock_data(string $rootPath): array
{
    return rr_default_content($rootPath);
}

/** @return list<array<string, mixed>> */
function rr_active_banners(): array
{
    try {
        return (new BannerService())->active();
    } catch (Throwable $throwable) {
        (new LoggerService())->warning('No se pudieron cargar banners publicos.', ['exception' => $throwable]);
        return [];
    }
}

/** @return list<array<string, mixed>> */
function rr_active_notices(): array
{
    try {
        return (new UrgentNoticeService())->active();
    } catch (Throwable $throwable) {
        (new LoggerService())->warning('No se pudieron cargar avisos urgentes publicos.', ['exception' => $throwable]);
        return [];
    }
}

function rr_render_public(string $page, string $title, string $active): void
{
    $rootPath = rr_bootstrap();

    View::render('layouts.public', [
        'active' => $active,
        'contentView' => $page,
        'mock' => rr_default_content($rootPath),
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
        'mock' => rr_default_content($rootPath),
        'pageTitle' => $title,
    ], $extra));
}
