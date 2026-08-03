<?php

declare(strict_types=1);

$autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';

if (is_file($autoloadPath)) {
    require_once $autoloadPath;
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Administrativo Radio Rumbo</title>
    <link rel="stylesheet" href="../public/assets/css/base.css">
</head>
<body>
    <main class="page-shell">
        <section class="status-panel">
            <p class="eyebrow">Fase 0</p>
            <h1>Panel Administrativo Radio Rumbo</h1>
            <p class="lead">Base administrativa en preparacion</p>

            <div class="grid">
                <article>
                    <h2>Roles permitidos</h2>
                    <ul>
                        <li>Super Admin</li>
                        <li>Admin</li>
                    </ul>
                </article>
                <article>
                    <h2>Modulos planificados</h2>
                    <ul>
                        <li>Dashboard administrativo basico</li>
                        <li>Noticias</li>
                        <li>Programacion</li>
                        <li>Equipo</li>
                        <li>Publicidad y contacto</li>
                    </ul>
                </article>
            </div>
        </section>
    </main>
</body>
</html>
