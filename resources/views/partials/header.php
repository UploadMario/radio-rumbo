<?php

declare(strict_types=1);

$navItems = [
    'home' => ['label' => 'Inicio', 'href' => '/'],
    'en-vivo' => ['label' => 'En vivo', 'href' => '/en-vivo/'],
    'programacion' => ['label' => 'Programacion', 'href' => '/programacion/'],
    'noticias' => ['label' => 'Noticias', 'href' => '/noticias/'],
    'podcasts' => ['label' => 'Podcasts', 'href' => '/podcasts/'],
    'equipo' => ['label' => 'Equipo', 'href' => '/equipo/'],
    'publicidad-contacto' => ['label' => 'Publicidad', 'href' => '/publicidad-contacto/'],
];
?>
<header class="site-header">
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <div class="container header-inner">
        <a class="brand" href="/" aria-label="Radio Rumbo inicio">
            <img src="/assets/img/radio-rumbo-mark.svg" alt="" width="42" height="42">
            <span>
                <strong><?= $e((string) ($station['name'] ?? 'Radio Rumbo')) ?></strong>
                <small>Huancayo</small>
            </span>
        </a>
        <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="main-nav">
            Menu
        </button>
        <nav class="main-nav" id="main-nav" aria-label="Navegacion principal">
            <?php foreach ($navItems as $key => $item): ?>
                <a class="<?= $active === $key ? 'is-active' : '' ?>" href="<?= $e($item['href']) ?>">
                    <?= $e($item['label']) ?>
                </a>
            <?php endforeach; ?>
            <a class="admin-link" href="/admin/">Admin</a>
        </nav>
    </div>
</header>
