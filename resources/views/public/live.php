<?php

declare(strict_types=1);

$schedule = is_array($mock['today_schedule'] ?? null) ? $mock['today_schedule'] : [];
?>
<section class="page-hero live-hero">
    <div class="container live-panel">
        <div>
            <span class="live-pill">EN VIVO</span>
            <h1><?= $e((string) ($currentProgram['name'] ?? 'Programa Matinal')) ?></h1>
            <p><?= $e((string) ($currentProgram['summary'] ?? 'Transmision visual en preparacion.')) ?></p>
            <dl class="meta-row">
                <div><dt>Conductor</dt><dd><?= $e((string) ($currentProgram['host'] ?? 'Conductor Principal')) ?></dd></div>
                <div><dt>Horario</dt><dd><?= $e((string) ($currentProgram['schedule'] ?? '06:00 - 09:00')) ?></dd></div>
                <div><dt>Stream</dt><dd><?= $e((string) ($station['stream_status'] ?? 'Stream simulado')) ?></dd></div>
            </dl>
        </div>
        <div class="big-player">
            <button class="big-play" type="button" data-player-toggle aria-label="Reproducir visual">▶</button>
            <div class="audio-waves large" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></div>
            <div class="hero-actions">
                <button class="btn btn-soft" type="button">Compartir</button>
                <button class="btn btn-primary" type="button"><?= $e((string) ($station['whatsapp_cabina'] ?? 'WhatsApp de cabina')) ?></button>
            </div>
        </div>
    </div>
</section>
<section class="container">
    <div class="section-heading">
        <span class="section-kicker">Sigue la senal</span>
        <h2>Proximos programas</h2>
    </div>
    <div class="schedule-list">
        <?php foreach (array_slice($schedule, 1) as $program): ?>
            <article>
                <time><?= $e((string) $program['time']) ?></time>
                <div>
                    <h3><?= $e((string) $program['name']) ?></h3>
                    <p><?= $e((string) $program['host']) ?> · <?= $e((string) $program['category']) ?></p>
                </div>
                <button type="button">Recordar</button>
            </article>
        <?php endforeach; ?>
    </div>
</section>
