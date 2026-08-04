<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\Public\ContactController;

$rootPath = rr_bootstrap();
$controller = new ContactController();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $controller->store(rr_mock_data($rootPath), $_POST);
    return;
}

$controller->index(rr_mock_data($rootPath));
