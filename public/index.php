<?php

declare(strict_types=1);

$rootPath = dirname(__DIR__);
$autoloadPath = $rootPath . '/vendor/autoload.php';

if (is_file($autoloadPath)) {
    require_once $autoloadPath;
}

if (class_exists(\Dotenv\Dotenv::class) && is_file($rootPath . '/.env')) {
    \Dotenv\Dotenv::createImmutable($rootPath)->safeLoad();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Radio Rumbo - Fase 0.1</title>
    <link rel="stylesheet" href="/assets/css/base.css">
</head>
<body>
    <main class="page-shell">
        <section class="status-panel">
            <p class="eyebrow">Fase 0.1</p>
            <h1>Radio Rumbo</h1>
            <p class="lead">Base del sistema inicializada</p>
            <p class="muted">Emisora local de Huancayo, Peru. Esta pantalla es un punto de partida tecnico, no el diseno final.</p>

            <nav class="placeholder-nav" aria-label="Modulos planificados">
                <a href="#">Home</a>
                <a href="#">En Vivo</a>
                <a href="#">Noticias</a>
                <a href="/admin/">Admin</a>
            </nav>
        </section>
    </main>
</body>
</html>
