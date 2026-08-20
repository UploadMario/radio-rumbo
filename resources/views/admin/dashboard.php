<?php

declare(strict_types=1);

$dashboard = is_array($mock['dashboard'] ?? null) ? $mock['dashboard'] : [];
$messages = is_array($dashboard['messages'] ?? null) ? $dashboard['messages'] : [];
$plannedModules = is_array($mock['planned_modules'] ?? null) ? $mock['planned_modules'] : [];
$station = is_array($mock['station'] ?? null) ? $mock['station'] : [];
$audioConfigured = !empty($station['stream_audio_url']);
$videoConfigured = !empty($station['live_video_widget_url']);
$facebookConfigured = !empty($station['facebook_page_url']);
$newsCounts = is_array($newsCounts ?? null) ? $newsCounts : ['total' => 0, 'published' => 0, 'draft' => 0];
$programCount = (int) ($programCount ?? 0);
$teamCount = (int) ($teamCount ?? 0);
$newMessages = (int) ($newMessages ?? 0);
$activeBanners = (int) ($activeBanners ?? 0);
$activeNotices = (int) ($activeNotices ?? 0);
$user = is_array($user ?? null) ? $user : ['name' => 'Admin', 'role' => 'Admin'];
$stats = [
    ['label' => 'Noticias', 'value' => (string) ($newsCounts['total'] ?? 0), 'hint' => 'Gestión editorial'],
    ['label' => 'Publicadas', 'value' => (string) ($newsCounts['published'] ?? 0), 'hint' => 'Visibles en la web'],
    ['label' => 'Programación', 'value' => (string) $programCount, 'hint' => 'Programas activos'],
    ['label' => 'Equipo', 'value' => (string) $teamCount, 'hint' => 'Integrantes activos'],
    ['label' => 'Mensajes', 'value' => (string) $newMessages, 'hint' => 'Pendientes de revisión'],
    ['label' => 'Banners', 'value' => (string) $activeBanners, 'hint' => 'Promociones activas'],
    ['label' => 'Avisos', 'value' => (string) $activeNotices, 'hint' => 'Comunicados visibles'],
    ['label' => 'Usuario', 'value' => (string) ($user['name'] ?? 'Admin'), 'hint' => (string) ($user['role'] ?? 'Admin')],
];
$presentationItems = [
    'Diseño público listo',
    'Panel administrativo operativo',
    'Contenido pendiente de confirmación oficial',
    'Despliegue final pendiente de configuración en hosting',
];
$pendingItems = [
    'Logo final oficial en buena calidad.',
    'WhatsApp de cabina.',
    'Programación oficial por día y horario.',
    'Nombre de programas.',
    'Conductores.',
    'Equipo oficial: nombres, cargos y fotos.',
    'Banners o campañas publicitarias iniciales.',
    'Confirmar correo institucional.',
    'Confirmar Facebook oficial principal.',
];
?>
<header class="admin-topbar">
    <div class="admin-title-block">
        <img class="admin-top-logo" src="/assets/img/radio-rumbo-logo.png" alt="" width="70" height="70">
        <div>
            <span>Panel Administrativo Radio Rumbo</span>
            <h1>Dashboard</h1>
        </div>
    </div>
    <div class="admin-profile">
        <span><?= $e((string) ($user['name'] ?? 'Admin')) ?> / <?= $e((string) ($user['role'] ?? 'Admin')) ?></span>
        <form action="/admin/logout/" method="post">
            <input type="hidden" name="_csrf_token" value="<?= $e(\App\Helpers\SecurityHelper::csrfToken()) ?>">
            <button type="submit">Cerrar sesión</button>
        </form>
    </div>
</header>

<section class="admin-hero-summary">
    <div>
        <span class="status-dot">Modo presentación</span>
        <h2>Sistema web Radio Rumbo</h2>
        <p>Módulos principales implementados y listos para carga de información oficial.</p>
    </div>
    <div class="presentation-status-list">
        <?php foreach ($presentationItems as $item): ?>
            <span><?= $e($item) ?></span>
        <?php endforeach; ?>
    </div>
</section>

<section class="admin-stats">
    <?php foreach ($stats as $stat): ?>
        <article>
            <span><?= $e($stat['label']) ?></span>
            <strong><?= $e($stat['value']) ?></strong>
            <small><?= $e($stat['hint']) ?></small>
        </article>
    <?php endforeach; ?>
</section>

<section class="admin-grid">
    <article class="admin-panel">
        <h2>Transmisión en vivo</h2>
        <p>El audio, video y Facebook están configurados como bloques independientes para mantener la presentación estable.</p>
        <div class="module-grid compact-status">
            <span>Audio - <?= $audioConfigured ? 'Configurado' : 'Pendiente' ?></span>
            <span>Video - <?= $videoConfigured ? 'Configurado' : 'Pendiente' ?></span>
            <span>Facebook - <?= $facebookConfigured ? 'Configurado' : 'Pendiente' ?></span>
        </div>
        <p class="admin-muted">La URL de audio está configurada, pero el proveedor puede reportar Stream is Offline cuando no hay AutoDJ o señal desde cabina.</p>
    </article>
    <article class="admin-panel">
        <h2>Accesos rápidos</h2>
        <div class="quick-admin">
            <a class="btn btn-primary" href="/admin/news/create/">Nueva noticia</a>
            <a class="btn btn-soft admin-soft" href="/admin/programs/create/">Nuevo programa</a>
            <a class="btn btn-soft admin-soft" href="/admin/team/create/">Nuevo integrante</a>
            <a class="btn btn-soft admin-soft" href="/admin/banners/create/">Nuevo banner</a>
            <a class="btn btn-soft admin-soft" href="/admin/urgent-notices/create/">Nuevo aviso</a>
            <a class="btn btn-soft admin-soft" href="/admin/messages/">Ver mensajes</a>
        </div>
    </article>
</section>

<section class="admin-grid">
    <article class="admin-panel wide">
        <h2>Módulos implementados</h2>
        <table>
            <thead><tr><th>Módulo</th><th>Estado</th><th>Uso en presentación</th></tr></thead>
            <tbody>
                <tr><td>Noticias</td><td>Funcional</td><td>Carga y publicación desde el panel</td></tr>
                <tr><td>Programación</td><td>Funcional</td><td>Horarios visibles en la web</td></tr>
                <tr><td>Equipo</td><td>Funcional</td><td>Integrantes activos publicados</td></tr>
                <tr><td>Mensajes</td><td>Funcional</td><td>Consultas desde contacto y publicidad</td></tr>
                <tr><td>Configuración</td><td>Funcional</td><td>Datos generales y medios en vivo</td></tr>
                <tr><td>Banners</td><td>Funcional</td><td>Campañas visibles en posiciones públicas</td></tr>
                <tr><td>Avisos urgentes</td><td>Funcional</td><td>Comunicados destacados para visitantes</td></tr>
            </tbody>
        </table>
    </article>
    <article class="admin-panel">
        <h2>Pendiente antes del despliegue final</h2>
        <ul class="pending-checklist">
            <?php foreach ($pendingItems as $item): ?>
                <li><?= $e($item) ?></li>
            <?php endforeach; ?>
        </ul>
    </article>
</section>

<section class="admin-grid">
    <article class="admin-panel">
        <h2>Mensajes recientes</h2>
        <?php if ($messages === []): ?>
            <p class="admin-muted">No hay mensajes recientes en la vista resumida.</p>
        <?php endif; ?>
        <?php foreach ($messages as $message): ?>
            <div class="message-item">
                <strong><?= $e((string) $message['from']) ?></strong>
                <span><?= $e((string) $message['subject']) ?></span>
                <small><?= $e((string) $message['status']) ?></small>
            </div>
        <?php endforeach; ?>
    </article>
    <article class="admin-panel">
        <h2>Módulos planificados</h2>
        <div class="module-grid compact-status">
            <?php foreach ($plannedModules as $module): ?>
                <span><?= $e((string) $module['name']) ?> - Próxima fase</span>
            <?php endforeach; ?>
            <span>Uploads - No incluido en esta versión de presentación</span>
            <span>Estadísticas avanzadas - Planificado</span>
        </div>
    </article>
</section>
