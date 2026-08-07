<?php

declare(strict_types=1);

$podcasts = is_array($mock['podcasts'] ?? null) ? $mock['podcasts'] : [];
?>
<section class="page-title container">
    <span class="section-kicker">Archivo sonoro</span>
    <h1>Podcasts</h1>
    <p>Módulo en preparación. Disponible para una próxima fase de publicación sonora.</p>
</section>
<section class="container presentation-note light-note">
    <strong>No incluido en esta versión de presentación</strong>
    <span>La sección queda preparada visualmente, sin reproducir episodios reales todavía.</span>
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
        <button type="button">Música</button>
    </div>
</section>
<section class="container card-grid">
    <?php foreach ($podcasts as $episode): ?>
        <article class="podcast-card">
            <span>Próxima fase</span>
            <h2><?= $e((string) $episode['title']) ?></h2>
            <p><?= $e((string) $episode['program']) ?> - <?= $e((string) $episode['duration']) ?></p>
            <button type="button" disabled>Disponible próximamente</button>
        </article>
    <?php endforeach; ?>
</section>
