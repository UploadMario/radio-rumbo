<?php

declare(strict_types=1);

require_once dirname(__DIR__, 3) . '/bootstrap.php';

use App\Controllers\Admin\NewsController;

$rootPath = rr_bootstrap();
$controller = new NewsController();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $controller->store(rr_default_content($rootPath), $_POST);
    return;
}

$controller->create(rr_default_content($rootPath));
