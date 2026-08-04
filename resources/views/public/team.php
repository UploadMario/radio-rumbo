<?php

declare(strict_types=1);

$team = is_array($mock['team'] ?? null) ? $mock['team'] : [];
?>
<section class="page-title container">
    <span class="section-kicker">Personas detrás de la señal</span>
    <h1>Nuestro equipo</h1>
    <p>Perfiles placeholder, sin nombres reales no proporcionados, listos para conectar contenido oficial.</p>
</section>
<section class="container team-grid">
    <?php foreach ($team as $member): ?>
        <article class="person-card large">
            <div class="avatar-placeholder"><?= $e(substr((string) $member['name'], 0, 1)) ?></div>
            <h2><?= $e((string) $member['name']) ?></h2>
            <p><?= $e((string) $member['role']) ?></p>
            <span><?= $e((string) $member['program']) ?></span>
        </article>
    <?php endforeach; ?>
</section>
