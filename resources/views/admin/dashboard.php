<?php

declare(strict_types=1);

$dashboard = is_array($mock['dashboard'] ?? null) ? $mock['dashboard'] : [];
$stats = is_array($dashboard['stats'] ?? null) ? $dashboard['stats'] : [];
$messages = is_array($dashboard['messages'] ?? null) ? $dashboard['messages'] : [];
$news = is_array($mock['news'] ?? null) ? $mock['news'] : [];
$plannedModules = is_array($mock['planned_modules'] ?? null) ? $mock['planned_modules'] : [];
?>
<header class="admin-topbar">
    <div class="admin-title-block">
        <img class="admin-top-logo" src="/assets/img/radio-rumbo-logo.png" alt="" width="70" height="70">
        <div>
            <span>Panel Administrativo Radio Rumbo</span>
            <h1>Dashboard visual</h1>
        </div>
    </div>
    <div class="admin-profile">
        <span>Admin / Super Admin</span>
        <a href="/admin/?view=login">Vista login</a>
    </div>
</header>

<section class="admin-stats">
    <?php foreach ($stats as $stat): ?>
        <article>
            <span><?= $e((string) $stat['label']) ?></span>
            <strong><?= $e((string) $stat['value']) ?></strong>
            <small><?= $e((string) $stat['hint']) ?></small>
        </article>
    <?php endforeach; ?>
</section>

<section class="admin-grid">
    <article class="admin-panel">
        <h2>Estado del stream</h2>
        <p>Stream simulado. La conexión real se realizará en fases posteriores.</p>
        <span class="status-dot">Visual activo</span>
    </article>
    <article class="admin-panel">
        <h2>Accesos rápidos</h2>
        <div class="quick-admin">
            <button type="button">Nueva noticia</button>
            <button type="button">Editar programación</button>
            <button type="button">Ver mensajes</button>
        </div>
    </article>
</section>

<section class="admin-grid">
    <article class="admin-panel wide">
        <h2>Noticias recientes</h2>
        <table>
            <thead><tr><th>Título</th><th>Categoría</th><th>Estado</th></tr></thead>
            <tbody>
                <?php foreach ($news as $item): ?>
                    <tr>
                        <td><?= $e((string) $item['title']) ?></td>
                        <td><?= $e((string) $item['category']) ?></td>
                        <td>Mock</td>
                    </tr>
                <?php endforeach; ?>
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
    <h2>Módulos del sistema</h2>
    <div class="module-grid">
        <?php foreach ($plannedModules as $module): ?>
            <span><?= $e((string) $module['name']) ?> · <?= $e((string) $module['status']) ?></span>
        <?php endforeach; ?>
    </div>
</section>
