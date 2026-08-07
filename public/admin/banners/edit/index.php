<?php

declare(strict_types=1);

require_once dirname(__DIR__, 3) . '/bootstrap.php';

use App\Controllers\Admin\BannerController;

$rootPath = rr_bootstrap();
$controller = new BannerController();
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $controller->update(rr_mock_data($rootPath), $id, $_POST);
    return;
}

$controller->edit(rr_mock_data($rootPath), $id);
