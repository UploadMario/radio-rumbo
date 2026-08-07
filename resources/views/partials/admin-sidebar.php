<?php

declare(strict_types=1);

$items = [
    'dashboard' => ['label' => 'Dashboard', 'href' => '/admin/dashboard/', 'status' => 'Funcional'],
    'noticias' => ['label' => 'Noticias', 'href' => '/admin/news/', 'status' => 'Funcional'],
    'programacion' => ['label' => 'Programacion', 'href' => '/admin/programs/', 'status' => 'Funcional'],
    'equipo' => ['label' => 'Equipo', 'href' => '/admin/team/', 'status' => 'Funcional'],
    'mensajes' => ['label' => 'Mensajes', 'href' => '/admin/messages/', 'status' => 'Funcional'],
    'configuracion' => ['label' => 'Configuracion', 'href' => '/admin/settings/', 'status' => 'Funcional'],
    'banners' => ['label' => 'Banners', 'href' => '/admin/banners/', 'status' => 'Funcional'],
    'avisos' => ['label' => 'Avisos urgentes', 'href' => '/admin/urgent-notices/', 'status' => 'Funcional'],
    'podcasts' => ['label' => 'Podcasts', 'href' => '#', 'status' => 'Proximamente'],
    'ranking' => ['label' => 'Ranking Top 20', 'href' => '#', 'status' => 'Proximamente'],
    'usuarios' => ['label' => 'Usuarios avanzados', 'href' => '#', 'status' => 'Proximamente'],
    'estadisticas' => ['label' => 'Estadisticas avanzadas', 'href' => '#', 'status' => 'Proximamente'],
];
?>
<aside class="admin-sidebar" aria-label="Navegacion administrativa">
    <a class="admin-brand" href="/admin/">
        <img class="admin-brand-logo" src="/assets/img/radio-rumbo-logo.png" alt="" width="56" height="56">
        <span>
            <strong>Radio Rumbo</strong>
            <small>Panel operativo</small>
        </span>
    </a>
    <nav>
        <?php foreach ($items as $key => $item): ?>
            <a class="<?= $active === $key ? 'is-active' : '' ?>" href="<?= $e($item['href']) ?>">
                <?= $e($item['label']) ?>
                <?php if ($key !== 'dashboard'): ?><span><?= $e($item['status']) ?></span><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="admin-role-box">
        <small>Roles permitidos</small>
        <strong>Super Admin · Admin</strong>
    </div>
</aside>
