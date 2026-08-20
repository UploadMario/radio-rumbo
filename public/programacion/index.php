<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\Public\ProgramController;

$rootPath = rr_bootstrap();
(new ProgramController())->index(rr_default_content($rootPath));
