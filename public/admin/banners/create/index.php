<?php

declare(strict_types=1);

require_once dirname(__DIR__, 3) . '/bootstrap.php';

use App\Controllers\Admin\BannerController;

$rootPath = rr_bootstrap();
$controller = new BannerController();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $controller->store(rr_mock_data($rootPath), $_POST);
    return;
}

$controller->create(rr_mock_data($rootPath));
