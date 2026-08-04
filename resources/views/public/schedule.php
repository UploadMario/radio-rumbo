<?php

declare(strict_types=1);

$schedule = is_array($mock['today_schedule'] ?? null) ? $mock['today_schedule'] : [];
$days = ['Hoy', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Fin de semana'];
?>
<section class="page-title container">
    <span class="section-kicker">Parrilla radial</span>
    <h1>Programación</h1>
    <p>Vista preparada para conectar horarios reales desde MySQL en fases futuras.</p>
</section>
<section class="container">
    <div class="filter-row" aria-label="Filtros visuales por día">
        <?php foreach ($days as $index => $day): ?>
            <button class="<?= $index === 0 ? 'is-active' : '' ?>" type="button"><?= $e($day) ?></button>
        <?php endforeach; ?>
    </div>
    <div class="schedule-list">
        <?php foreach ($schedule as $program): ?>
            <article class="<?= (bool) $program['live'] ? 'is-live' : '' ?>">
                <time><?= $e((string) $program['time']) ?></time>
                <div>
                    <h2><?= $e((string) $program['name']) ?></h2>
                    <p><?= $e((string) $program['host']) ?> · <?= $e((string) $program['category']) ?></p>
                </div>
                <?php if ((bool) $program['live']): ?><span class="live-pill">En vivo ahora</span><?php endif; ?>
                <button type="button">Ver programa</button>
            </article>
        <?php endforeach; ?>
    </div>
</section>
