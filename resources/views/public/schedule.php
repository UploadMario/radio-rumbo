<?php

declare(strict_types=1);

$programs = is_array($programs ?? null) ? $programs : [];
$dayLabels = [
    'monday' => 'Lunes',
    'tuesday' => 'Martes',
    'wednesday' => 'Miércoles',
    'thursday' => 'Jueves',
    'friday' => 'Viernes',
    'saturday' => 'Sábado',
    'sunday' => 'Domingo',
];
$todayKey = strtolower(date('l'));
$now = date('H:i:s');
?>
<section class="page-title container">
    <span class="section-kicker">Parrilla radial</span>
    <h1>Programación</h1>
    <p>Consulta los programas, horarios y voces que acompañan la señal de Radio Rumbo.</p>
</section>
<section class="container">
    <div class="filter-row" aria-label="Filtros visuales por día">
        <?php foreach ($dayLabels as $value => $day): ?>
            <button class="<?= $value === $todayKey ? 'is-active' : '' ?>" type="button"><?= $e($day) ?></button>
        <?php endforeach; ?>
    </div>
    <div class="schedule-list public-schedule-list">
        <?php if ($programs === []): ?>
            <article class="presentation-empty">
                <div>
                    <h2>La programación estará disponible próximamente.</h2>
                    <p>Vuelve pronto para conocer los horarios de Radio Rumbo.</p>
                </div>
            </article>
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
                    <p><?= $e($dayLabels[(string) $program['day_of_week']] ?? (string) $program['day_of_week']) ?> - <?= $e((string) ($program['host_name'] ?: 'Cabina Radio Rumbo')) ?> - <?= $e((string) ($program['category'] ?: 'Programación')) ?></p>
                    <?php if ((string) ($program['description'] ?? '') !== ''): ?><p><?= $e((string) $program['description']) ?></p><?php endif; ?>
                </div>
                <?php if ($isLive): ?><span class="live-pill">En vivo ahora</span><?php endif; ?>
                <a class="btn btn-soft admin-soft" href="/en-vivo/">Ver señal</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
