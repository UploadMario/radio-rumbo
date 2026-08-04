<?php

declare(strict_types=1);

$items = [
    'dashboard' => 'Dashboard',
    'noticias' => 'Noticias',
    'programacion' => 'Programacion',
    'podcasts' => 'Podcasts',
    'ranking' => 'Ranking Top 20',
    'equipo' => 'Equipo',
    'banners' => 'Publicidad / Banners',
    'mensajes' => 'Mensajes',
    'avisos' => 'Avisos urgentes',
    'configuracion' => 'Configuracion',
    'usuarios' => 'Usuarios',
];
?>
<aside class="admin-sidebar" aria-label="Navegacion administrativa">
    <a class="admin-brand" href="/admin/">
        <img src="/assets/img/radio-rumbo-mark.svg" alt="" width="40" height="40">
        <span>
            <strong>Radio Rumbo</strong>
            <small>Panel visual Fase 1</small>
        </span>
    </a>
    <nav>
        <?php foreach ($items as $key => $label): ?>
            <a class="<?= $active === $key ? 'is-active' : '' ?>" href="#">
                <?= $e($label) ?>
                <?php if ($key !== 'dashboard'): ?>
                    <span>Proximamente</span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="admin-role-box">
        <small>Roles permitidos</small>
        <strong>Super Admin · Admin</strong>
    </div>
</aside>
