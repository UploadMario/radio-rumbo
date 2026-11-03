<?php

declare(strict_types=1);

$programs = is_array($programs ?? null) ? $programs : [];
$dayLabels = is_array($dayLabels ?? null) ? $dayLabels : [];
$selectedDay = is_string($selectedDay ?? null) ? $selectedDay : 'lunes';
$todayKey = is_string($todayKey ?? null) ? $todayKey : $selectedDay;
$now = (new DateTimeImmutable('now', new DateTimeZone('America/Lima')))->format('H:i');
?>
<section class="page-title container schedule-page-title">
    <span class="section-kicker">Parrilla radial</span>
    <h1>Programación</h1>
    <p>Consulta los programas, horarios y voces que acompañan la señal de Radio Rumbo.</p>
    <div class="schedule-global-actions">
        <button class="btn btn-primary" type="button" data-player-toggle>Escuchar en vivo</button>
        <a class="btn btn-soft" href="/en-vivo/">Ver cabina</a>
    </div>
</section>
<section class="container schedule-page">
    <nav class="filter-row schedule-day-filter" aria-label="Seleccionar día de programación">
        <?php foreach ($dayLabels as $value => $day): ?>
            <a
                class="<?= $value === $selectedDay ? 'is-active' : '' ?>"
                href="/programacion/?dia=<?= $e((string) $value) ?>"
                <?= $value === $selectedDay ? 'aria-current="page"' : '' ?>
            >
                <span><?= $e((string) $day) ?></span>
                <?php if ($value === $todayKey): ?><small>Hoy</small><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="schedule-list public-schedule-list schedule-timeline">
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
            $isLive = $selectedDay === $todayKey
                && (string) $program['start_time'] <= $now
                && (string) $program['end_time'] > $now;
            ?>
            <article class="<?= $isLive ? 'is-live' : '' ?>">
                <time>
                    <span><?= $e(substr((string) $program['start_time'], 0, 5)) ?></span>
                    <small><?= $e(substr((string) $program['end_time'], 0, 5)) ?></small>
                </time>
                <div>
                    <h2><?= $e((string) $program['name']) ?></h2>
                    <p><?= $e((string) ($program['host'] ?? $program['host_name'] ?? 'Cabina Radio Rumbo')) ?> - <?= $e((string) ($program['category'] ?: 'Programación')) ?></p>
                    <?php if ((string) ($program['summary'] ?? '') !== ''): ?><p><?= $e((string) $program['summary']) ?></p><?php endif; ?>
                </div>
                <?php if ($isLive): ?><span class="live-pill">En vivo ahora</span><?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
