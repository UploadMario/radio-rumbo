<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\Public\TeamController;

$rootPath = rr_bootstrap();
(new TeamController())->index(rr_mock_data($rootPath));
