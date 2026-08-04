<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

use App\Controllers\Admin\DashboardController;

$rootPath = rr_bootstrap();
(new DashboardController())->index(rr_mock_data($rootPath));
