<?php

declare(strict_types=1);

$newsCounts = is_array($newsCounts ?? null) ? $newsCounts : ['total' => 0, 'published' => 0, 'draft' => 0];
$programCount = (int) ($programCount ?? 0);
$teamCount = (int) ($teamCount ?? 0);
$newMessages = (int) ($newMessages ?? 0);
$activeBanners = (int) ($activeBanners ?? 0);
$activeNotices = (int) ($activeNotices ?? 0);
$user = is_array($user ?? null) ? $user : ['name' => 'Admin', 'role' => 'Admin'];
$stats = [
    ['label' => 'Noticias publicadas', 'value' => (string) ($newsCounts['published'] ?? 0), 'hint' => 'Contenido visible'],
    ['label' => 'Programas activos', 'value' => (string) $programCount, 'hint' => 'En programación'],
    ['label' => 'Integrantes activos', 'value' => (string) $teamCount, 'hint' => 'Equipo publicado'],
    ['label' => 'Mensajes nuevos', 'value' => (string) $newMessages, 'hint' => 'Requieren revisión'],
];
?>
<header class="admin-topbar dashboard-header">
    <div>
        <span>Gestión de contenidos</span>
        <h1>Dashboard</h1>
    </div>
    <div class="admin-profile">
        <strong><?= $e((string) ($user['name'] ?? 'Admin')) ?></strong>
        <span><?= $e((string) ($user['role'] ?? 'Admin')) ?></span>
        <form action="/admin/logout/" method="post">
            <input type="hidden" name="_csrf_token" value="<?= $e(\App\Helpers\SecurityHelper::csrfToken()) ?>">
            <button type="submit">Cerrar sesión</button>
        </form>
    </div>
</header>

<section class="admin-stats" aria-label="Resumen de contenidos">
    <?php foreach ($stats as $stat): ?>
        <article>
            <span><?= $e($stat['label']) ?></span>
            <strong><?= $e($stat['value']) ?></strong>
            <small><?= $e($stat['hint']) ?></small>
        </article>
    <?php endforeach; ?>
</section>

<section class="admin-dashboard-grid">
    <article class="admin-panel">
        <div class="panel-heading">
            <div><span class="panel-kicker">Trabajo diario</span><h2>Acciones rápidas</h2></div>
        </div>
        <div class="quick-admin dashboard-actions">
            <a class="btn btn-primary" href="/admin/news/create/">Nueva noticia</a>
            <a class="btn btn-soft admin-soft" href="/admin/programs/create/">Nuevo programa</a>
            <a class="btn btn-soft admin-soft" href="/admin/team/create/">Nuevo integrante</a>
            <a class="btn btn-soft admin-soft" href="/admin/banners/create/">Nuevo banner</a>
            <a class="btn btn-soft admin-soft" href="/admin/urgent-notices/create/">Nuevo aviso</a>
            <a class="btn btn-soft admin-soft" href="/admin/messages/">Revisar mensajes</a>
        </div>
    </article>

    <article class="admin-panel">
        <div class="panel-heading">
            <div><span class="panel-kicker">Estado de contenidos</span><h2>Publicación y vigencia</h2></div>
        </div>
        <dl class="dashboard-list">
            <div><dt>Noticias en borrador</dt><dd><?= $e((string) ($newsCounts['draft'] ?? 0)) ?></dd></div>
            <div><dt>Banners activos</dt><dd><?= $e((string) $activeBanners) ?></dd></div>
            <div><dt>Avisos activos</dt><dd><?= $e((string) $activeNotices) ?></dd></div>
        </dl>
    </article>
</section>

<section class="admin-panel dashboard-module-panel">
    <div class="panel-heading">
        <div><span class="panel-kicker">Módulos</span><h2>Administración de contenidos</h2></div>
    </div>
    <div class="module-links">
        <a href="/admin/news/"><strong>Noticias</strong><span>Crear, editar y publicar</span></a>
        <a href="/admin/programs/"><strong>Programación</strong><span>Gestionar horarios</span></a>
        <a href="/admin/team/"><strong>Equipo</strong><span>Gestionar integrantes</span></a>
        <a href="/admin/messages/"><strong>Mensajes</strong><span>Revisar consultas</span></a>
        <a href="/admin/banners/"><strong>Banners</strong><span>Gestionar promociones</span></a>
        <a href="/admin/urgent-notices/"><strong>Avisos urgentes</strong><span>Gestionar comunicados</span></a>
    </div>
</section>
