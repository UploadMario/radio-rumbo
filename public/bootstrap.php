<?php

declare(strict_types=1);

use App\Core\View;
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

    return is_array($data) ? $data : [];
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

function rr_render_admin(string $page, string $title, string $active = 'dashboard'): void
{
    $rootPath = rr_bootstrap();

    View::render('layouts.admin', [
        'active' => $active,
        'contentView' => $page,
        'mock' => rr_mock_data($rootPath),
        'pageTitle' => $title,
    ]);
}
