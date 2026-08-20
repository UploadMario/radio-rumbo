<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

use App\Controllers\Admin\SettingController;

$rootPath = rr_bootstrap();
$controller = new SettingController();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $controller->update(rr_default_content($rootPath), $_POST);
    return;
}

$controller->index(rr_default_content($rootPath));
