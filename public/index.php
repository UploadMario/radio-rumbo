<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use App\Controllers\Public\NewsController;
use App\Controllers\Public\ProgramController;

$path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$routes = [
    '' => ['home', 'Inicio'],
    'en-vivo' => ['live', 'En Vivo'],
    'programacion' => ['schedule', 'Programación'],
    'noticias' => ['news', 'Noticias'],
    'podcasts' => ['podcasts', 'Podcasts'],
    'equipo' => ['team', 'Nuestro Equipo'],
    'publicidad-contacto' => ['advertising-contact', 'Publicidad y Contacto'],
];

[$page, $title] = $routes[$path] ?? ['upcoming', 'Módulo en preparación'];

if (!array_key_exists($path, $routes) && $path !== '') {
    http_response_code(404);
}

if ($page === 'news') {
    $rootPath = rr_bootstrap();
    (new NewsController())->index(rr_default_content($rootPath));
    return;
}

if ($page === 'schedule') {
    $rootPath = rr_bootstrap();
    (new ProgramController())->index(rr_default_content($rootPath));
    return;
}

rr_render_public($page, 'Radio Rumbo - ' . $title, $path === '' ? 'home' : $path);
