<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

use App\Controllers\Admin\AuthController;

$rootPath = rr_bootstrap();
$controller = new AuthController();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $controller->login(rr_default_content($rootPath), $_POST);
    return;
}

$controller->showLogin(rr_default_content($rootPath));
