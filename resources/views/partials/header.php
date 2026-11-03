<?php

declare(strict_types=1);

$navItems = [
    'home' => ['label' => 'Inicio', 'href' => '/'],
    'en-vivo' => ['label' => 'En vivo', 'href' => '/en-vivo/'],
    'programacion' => ['label' => 'Programación', 'href' => '/programacion/'],
    'noticias' => ['label' => 'Noticias', 'href' => '/noticias/'],
    'equipo' => ['label' => 'Equipo', 'href' => '/equipo/'],
    'publicidad-contacto' => ['label' => 'Publicidad', 'href' => '/publicidad-contacto/'],
];
?>
<header class="site-header">
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <div class="container header-inner">
        <a class="brand" href="/" aria-label="Radio Rumbo inicio">
            <img class="brand-logo" src="/assets/img/radio-rumbo-wordmark-web.png" alt="Radio Rumbo" width="182" height="30">
        </a>
        <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="main-nav">
            Menú
        </button>
        <nav class="main-nav" id="main-nav" aria-label="Navegación principal">
            <?php foreach ($navItems as $key => $item): ?>
                <a class="<?= $active === $key ? 'is-active' : '' ?>" href="<?= $e($item['href']) ?>">
                    <?= $e($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
