<?php

declare(strict_types=1);

$items = [
    'dashboard' => 'Dashboard',
    'noticias' => 'Noticias',
    'programacion' => 'Programación',
    'podcasts' => 'Podcasts',
    'ranking' => 'Ranking Top 20',
    'equipo' => 'Equipo',
    'banners' => 'Publicidad / Banners',
    'mensajes' => 'Mensajes',
    'avisos' => 'Avisos urgentes',
    'configuracion' => 'Configuración',
    'usuarios' => 'Usuarios',
];
?>
<aside class="admin-sidebar" aria-label="Navegación administrativa">
    <a class="admin-brand" href="/admin/">
        <img class="admin-brand-logo" src="/assets/img/radio-rumbo-logo.png" alt="" width="56" height="56">
        <span>
            <strong>Radio Rumbo</strong>
            <small>Panel visual Fase 1.1</small>
        </span>
    </a>
    <nav>
        <?php foreach ($items as $key => $label): ?>
            <a class="<?= $active === $key ? 'is-active' : '' ?>" href="#">
                <?= $e($label) ?>
                <?php if ($key !== 'dashboard'): ?>
                    <span>Próximamente</span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="admin-role-box">
        <small>Roles permitidos</small>
        <strong>Super Admin · Admin</strong>
    </div>
</aside>
