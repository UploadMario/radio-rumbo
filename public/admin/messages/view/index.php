<?php

declare(strict_types=1);

require_once dirname(__DIR__, 3) . '/bootstrap.php';

use App\Controllers\Admin\MessageController;

$rootPath = rr_bootstrap();
$id = (int) ($_GET['id'] ?? 0);
(new MessageController())->view(rr_mock_data($rootPath), $id);
