<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\Admin\DashboardController;
use App\Services\SessionService;

$rootPath = rr_bootstrap();
$mock = rr_mock_data($rootPath);
$session = new SessionService();

if ($session->user() === null) {
    header('Location: /admin/login/', true, 302);
    exit;
}

(new DashboardController($session))->index($mock);
