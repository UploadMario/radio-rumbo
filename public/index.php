<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$routes = [
    '' => ['home', 'Inicio'],
    'en-vivo' => ['live', 'En Vivo'],
    'programacion' => ['schedule', 'Programacion'],
    'noticias' => ['news', 'Noticias'],
    'podcasts' => ['podcasts', 'Podcasts'],
    'equipo' => ['team', 'Nuestro Equipo'],
    'publicidad-contacto' => ['advertising-contact', 'Publicidad y Contacto'],
];

[$page, $title] = $routes[$path] ?? ['upcoming', 'Modulo en preparacion'];

if (!array_key_exists($path, $routes) && $path !== '') {
    http_response_code(404);
}

rr_render_public($page, 'Radio Rumbo - ' . $title, $path === '' ? 'home' : $path);
