<?php

declare(strict_types=1);

$dashboard = is_array($mock['dashboard'] ?? null) ? $mock['dashboard'] : [];
$messages = is_array($dashboard['messages'] ?? null) ? $dashboard['messages'] : [];
$plannedModules = is_array($mock['planned_modules'] ?? null) ? $mock['planned_modules'] : [];
$newsCounts = is_array($newsCounts ?? null) ? $newsCounts : ['total' => 0, 'published' => 0, 'draft' => 0];
$user = is_array($user ?? null) ? $user : ['name' => 'Admin', 'role' => 'Admin'];
$stats = [
    ['label' => 'Noticias totales', 'value' => (string) ($newsCounts['total'] ?? 0), 'hint' => 'Dato real desde MySQL'],
    ['label' => 'Publicadas', 'value' => (string) ($newsCounts['published'] ?? 0), 'hint' => 'Estado published'],
    ['label' => 'Borradores', 'value' => (string) ($newsCounts['draft'] ?? 0), 'hint' => 'Estado draft'],
    ['label' => 'Usuario actual', 'value' => (string) ($user['name'] ?? 'Admin'), 'hint' => (string) ($user['role'] ?? 'Admin')],
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
        <a href="/admin/logout/">Cerrar sesión</a>
    </div>
</header>

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
        <h2>Estado del stream</h2>
        <p>Stream configurado como valor general. La reproducción real se conectará en una fase posterior.</p>
        <span class="status-dot">Configuración preparada</span>
    </article>
    <article class="admin-panel">
        <h2>Accesos rápidos</h2>
        <div class="quick-admin">
            <a class="btn btn-primary" href="/admin/news/create/">Nueva noticia</a>
            <a class="btn btn-soft admin-soft" href="/admin/news/">Gestionar noticias</a>
        </div>
    </article>
</section>

<section class="admin-grid">
    <article class="admin-panel wide">
        <h2>Módulos funcionales Fase 2.0</h2>
        <table>
            <thead><tr><th>Módulo</th><th>Estado</th><th>Alcance</th></tr></thead>
            <tbody>
                <tr><td>Autenticación</td><td>Funcional</td><td>Login, sesión y logout</td></tr>
                <tr><td>Noticias</td><td>Funcional mínimo</td><td>Crear, editar, publicar y archivar</td></tr>
                <tr><td>Configuración</td><td>Básica</td><td>Lectura desde settings con fallback</td></tr>
            </tbody>
        </table>
    </article>
    <article class="admin-panel">
        <h2>Mensajes recientes</h2>
        <?php foreach ($messages as $message): ?>
            <div class="message-item">
                <strong><?= $e((string) $message['from']) ?></strong>
                <span><?= $e((string) $message['subject']) ?></span>
                <small><?= $e((string) $message['status']) ?></small>
            </div>
        <?php endforeach; ?>
    </article>
</section>

<section class="admin-panel">
    <h2>Módulos planificados</h2>
    <div class="module-grid">
        <?php foreach ($plannedModules as $module): ?>
            <span><?= $e((string) $module['name']) ?> · <?= $e((string) $module['status']) ?></span>
        <?php endforeach; ?>
    </div>
</section>
