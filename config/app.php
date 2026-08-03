<?php

declare(strict_types=1);

return [
    'name' => 'Radio Rumbo',
    'env' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
    'stream_url' => $_ENV['STREAM_URL'] ?? 'https://example.com/stream',
];
