<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\Public\NewsController;

$rootPath = rr_bootstrap();
(new NewsController())->index(rr_mock_data($rootPath));
