<?php

declare(strict_types=1);

$items = is_array($newsItems ?? null) ? $newsItems : [];
$message = is_string($newsLoadMessage ?? null) ? $newsLoadMessage : null;
$featured = $items[0] ?? null;
?>
<section class="section-light news-page">
    <div class="container page-title">
        <span class="section-kicker">Actualidad</span>
        <h1>Noticias</h1>
        <p>Noticias publicadas desde el backend mínimo funcional.</p>
    </div>
    <div class="container search-toolbar">
        <label>
            <span>Buscar noticia</span>
            <input type="search" placeholder="Buscar por título o categoría">
        </label>
        <div class="filter-row">
            <button class="is-active" type="button">Todas</button>
            <button type="button">Huancayo</button>
            <button type="button">Comunidad</button>
            <button type="button">Cultura</button>
        </div>
    </div>

    <?php if ($message !== null): ?>
        <div class="container empty-state"><?= $e($message) ?></div>
    <?php endif; ?>

    <?php if ($items === [] && $message === null): ?>
        <div class="container empty-state">Aún no hay noticias publicadas.</div>
    <?php endif; ?>

    <?php if (is_array($featured)): ?>
        <article class="container featured-news">
            <span><?= $e((string) ($featured['category'] ?? 'Huancayo')) ?> · <?= $e((string) ($featured['published_at'] ?? 'Pendiente')) ?></span>
            <h2><?= $e((string) ($featured['title'] ?? 'Últimas noticias de Huancayo')) ?></h2>
            <p><?= $e((string) ($featured['summary'] ?? '')) ?></p>
            <a class="btn btn-primary" href="#">Leer más</a>
        </article>
    <?php endif; ?>

    <?php if (count($items) > 1): ?>
        <div class="container card-grid">
            <?php foreach (array_slice($items, 1) as $item): ?>
                <article class="news-card">
                    <span><?= $e((string) ($item['category'] ?? 'General')) ?> · <?= $e((string) ($item['published_at'] ?? 'Pendiente')) ?></span>
                    <h3><?= $e((string) ($item['title'] ?? 'Noticia')) ?></h3>
                    <p><?= $e((string) ($item['summary'] ?? '')) ?></p>
                    <a href="#">Leer más</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
