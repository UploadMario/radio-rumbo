<?php

declare(strict_types=1);

$programs = is_array($programs ?? null) ? $programs : [];
$dayLabels = [
    'monday' => 'Lunes',
    'tuesday' => 'Martes',
    'wednesday' => 'Miercoles',
    'thursday' => 'Jueves',
    'friday' => 'Viernes',
    'saturday' => 'Sabado',
    'sunday' => 'Domingo',
];
$todayKey = strtolower(date('l'));
$now = date('H:i:s');
?>
<section class="page-title container">
    <span class="section-kicker">Parrilla radial</span>
    <h1>Programacion</h1>
    <p>Programas activos de Radio Rumbo, listos para actualizarse desde el panel administrativo.</p>
</section>
<section class="container">
    <div class="filter-row" aria-label="Filtros visuales por dia">
        <?php foreach ($dayLabels as $value => $day): ?>
            <button class="<?= $value === $todayKey ? 'is-active' : '' ?>" type="button"><?= $e($day) ?></button>
        <?php endforeach; ?>
    </div>
    <div class="schedule-list">
        <?php if ($programs === []): ?>
            <article><div><h2>La programacion estara disponible proximamente.</h2><p>El equipo administrativo podra cargar horarios desde el panel.</p></div></article>
        <?php endif; ?>
        <?php foreach ($programs as $program): ?>
            <?php
            $isLive = (string) $program['day_of_week'] === $todayKey
                && (string) $program['start_time'] <= $now
                && (string) $program['end_time'] > $now;
            ?>
            <article class="<?= $isLive ? 'is-live' : '' ?>">
                <time><?= $e(substr((string) $program['start_time'], 0, 5)) ?> - <?= $e(substr((string) $program['end_time'], 0, 5)) ?></time>
                <div>
                    <h2><?= $e((string) $program['name']) ?></h2>
                    <p><?= $e($dayLabels[(string) $program['day_of_week']] ?? (string) $program['day_of_week']) ?> · <?= $e((string) ($program['host_name'] ?: 'Conductor principal')) ?> · <?= $e((string) ($program['category'] ?: 'Programacion')) ?></p>
                    <?php if ((string) ($program['description'] ?? '') !== ''): ?><p><?= $e((string) $program['description']) ?></p><?php endif; ?>
                </div>
                <?php if ($isLive): ?><span class="live-pill">En vivo ahora</span><?php endif; ?>
                <button type="button">Ver programa</button>
            </article>
        <?php endforeach; ?>
    </div>
</section>
