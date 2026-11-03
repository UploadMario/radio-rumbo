<?php

declare(strict_types=1);

$items = [
    'dashboard' => ['label' => 'Resumen', 'href' => '/admin/dashboard/'],
    'noticias' => ['label' => 'Noticias', 'href' => '/admin/news/'],
    'programacion' => ['label' => 'Programación', 'href' => '/admin/programs/'],
    'equipo' => ['label' => 'Equipo', 'href' => '/admin/team/'],
    'mensajes' => ['label' => 'Mensajes', 'href' => '/admin/messages/'],
    'configuracion' => ['label' => 'Configuración', 'href' => '/admin/settings/'],
    'banners' => ['label' => 'Banners', 'href' => '/admin/banners/'],
    'avisos' => ['label' => 'Avisos urgentes', 'href' => '/admin/urgent-notices/'],
];
?>
<button class="admin-mobile-toggle" type="button" aria-controls="admin-navigation" aria-expanded="false" data-admin-nav-toggle>
    <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" focusable="false">
        <path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" />
    </svg>
    Menú administrativo
</button>
<aside class="admin-sidebar" aria-label="Navegacion administrativa">
    <a class="admin-brand" href="/admin/">
        <img class="admin-brand-logo" src="/assets/img/radio-rumbo-logo.png" alt="" width="56" height="56">
        <span>
            <strong>Radio Rumbo</strong>
            <small>Panel operativo</small>
        </span>
    </a>
    <nav id="admin-navigation">
        <?php foreach ($items as $key => $item): ?>
            <a class="<?= $active === $key ? 'is-active' : '' ?>" href="<?= $e($item['href']) ?>">
                <?= $e($item['label']) ?>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
