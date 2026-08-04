<?php

declare(strict_types=1);

$podcasts = is_array($mock['podcasts'] ?? null) ? $mock['podcasts'] : [];
?>
<section class="page-title container">
    <span class="section-kicker">Archivo sonoro</span>
    <h1>Podcasts</h1>
    <p>Modulo en preparacion. Los episodios son placeholders para validar la experiencia visual.</p>
</section>
<section class="container search-toolbar">
    <label>
        <span>Buscar episodio</span>
        <input type="search" placeholder="Programa, tema o conductor">
    </label>
    <div class="filter-row">
        <button class="is-active" type="button">Todos</button>
        <button type="button">Noticias</button>
        <button type="button">Entrevistas</button>
        <button type="button">Musica</button>
    </div>
</section>
<section class="container card-grid">
    <?php foreach ($podcasts as $episode): ?>
        <article class="podcast-card">
            <span><?= $e((string) $episode['status']) ?></span>
            <h2><?= $e((string) $episode['title']) ?></h2>
            <p><?= $e((string) $episode['program']) ?> · <?= $e((string) $episode['duration']) ?></p>
            <button type="button" disabled>Reproducir visual</button>
        </article>
    <?php endforeach; ?>
</section>
