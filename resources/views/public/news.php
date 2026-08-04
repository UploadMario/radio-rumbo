<?php

declare(strict_types=1);

$news = is_array($mock['news'] ?? null) ? $mock['news'] : [];
$featured = $news[0] ?? [];
?>
<section class="section-light news-page">
    <div class="container page-title">
        <span class="section-kicker">Actualidad</span>
        <h1>Noticias</h1>
        <p>Contenido mock con estructura lista para conectarse a publicaciones reales.</p>
    </div>
    <div class="container search-toolbar">
        <label>
            <span>Buscar noticia</span>
            <input type="search" placeholder="Buscar por titulo o categoria">
        </label>
        <div class="filter-row">
            <button class="is-active" type="button">Todas</button>
            <button type="button">Huancayo</button>
            <button type="button">Comunidad</button>
            <button type="button">Cultura</button>
        </div>
    </div>
    <article class="container featured-news">
        <span><?= $e((string) ($featured['category'] ?? 'Huancayo')) ?> · <?= $e((string) ($featured['date'] ?? 'Fase 1')) ?></span>
        <h2><?= $e((string) ($featured['title'] ?? 'Ultimas noticias de Huancayo')) ?></h2>
        <p><?= $e((string) ($featured['summary'] ?? 'Noticia destacada placeholder.')) ?></p>
        <a class="btn btn-primary" href="#">Leer mas</a>
    </article>
    <div class="container card-grid">
        <?php foreach (array_slice($news, 1) as $item): ?>
            <article class="news-card">
                <span><?= $e((string) $item['category']) ?> · <?= $e((string) $item['date']) ?></span>
                <h3><?= $e((string) $item['title']) ?></h3>
                <p><?= $e((string) $item['summary']) ?></p>
                <a href="#">Leer mas</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
