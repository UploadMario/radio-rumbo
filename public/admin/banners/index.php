<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

use App\Controllers\Admin\BannerController;

$rootPath = rr_bootstrap();
$controller = new BannerController();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $controller->action($_POST);
    return;
}

$controller->index(rr_mock_data($rootPath));
