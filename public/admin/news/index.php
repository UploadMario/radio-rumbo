<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

use App\Controllers\Admin\NewsController;

$rootPath = rr_bootstrap();
$controller = new NewsController();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['_action'] ?? '') === 'archive') {
    $controller->archive($_POST);
    return;
}

$controller->index(rr_mock_data($rootPath));
