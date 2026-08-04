<?php

declare(strict_types=1);

$schedule = is_array($mock['today_schedule'] ?? null) ? $mock['today_schedule'] : [];
$audioUrl = is_string($station['stream_audio_url'] ?? null) ? $station['stream_audio_url'] : '';
$videoUrl = is_string($station['live_video_widget_url'] ?? null) ? $station['live_video_widget_url'] : '';
$facebookUrl = is_string($station['facebook_page_url'] ?? null) ? $station['facebook_page_url'] : '';
$facebookPluginUrl = is_string($station['facebook_page_plugin_url'] ?? null) ? $station['facebook_page_plugin_url'] : '';
?>
<section class="page-hero live-hero">
    <div class="container live-panel">
        <div>
            <img class="section-logo" src="/assets/img/radio-rumbo-logo.png" alt="Radio Rumbo" width="112" height="112">
            <span class="live-pill">EN VIVO</span>
            <h1><?= $e((string) ($currentProgram['name'] ?? 'Programa matinal')) ?></h1>
            <p><?= $e((string) ($currentProgram['summary'] ?? 'Transmisión en preparación.')) ?></p>
            <p>Mira también la transmisión en vivo de cabina y programas especiales.</p>
            <dl class="meta-row">
                <div><dt>Conductor</dt><dd><?= $e((string) ($currentProgram['host'] ?? 'Conductor principal')) ?></dd></div>
                <div><dt>Horario</dt><dd><?= $e((string) ($currentProgram['schedule'] ?? '06:00 - 09:00')) ?></dd></div>
                <div><dt>Audio</dt><dd><?= $audioUrl !== '' ? 'Audio configurado' : 'Pendiente de configurar' ?></dd></div>
            </dl>
        </div>
        <?php require dirname(__DIR__) . '/partials/live-video-player.php'; ?>
    </div>
</section>

<section class="container live-media-grid">
    <article class="media-card">
        <span class="section-kicker">Radio en vivo</span>
        <h2>Audio de Radio Rumbo</h2>
        <p>El audio permanece disponible en el reproductor flotante inferior y en este bloque secundario.</p>
        <?php if ($audioUrl !== ''): ?>
            <audio data-audio-player preload="none" controls src="<?= $e($audioUrl) ?>"></audio>
        <?php else: ?>
            <p class="embed-fallback">Audio en vivo pendiente de configuración.</p>
        <?php endif; ?>
    </article>
    <article class="media-card" id="facebook-en-vivo">
        <span class="section-kicker">Facebook oficial</span>
        <h2>Noticias en vivo desde Facebook</h2>
        <p>Últimas publicaciones de Radio Rumbo Huancayo.</p>
        <?php if ($facebookPluginUrl !== ''): ?>
            <iframe
                src="<?= $e($facebookPluginUrl) ?>"
                title="Noticias en vivo desde Facebook de Radio Rumbo"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
            <p class="embed-fallback">Si el bloque no carga, puede deberse a cookies, navegador o políticas externas de Facebook.</p>
        <?php else: ?>
            <p class="embed-fallback">Facebook pendiente de configuración.</p>
        <?php endif; ?>
        <?php if ($facebookUrl !== ''): ?>
            <a class="btn btn-primary" href="<?= $e($facebookUrl) ?>" target="_blank" rel="noopener noreferrer">Ver en Facebook</a>
        <?php endif; ?>
    </article>
</section>

<section class="container">
    <div class="section-heading">
        <span class="section-kicker">Sigue la señal</span>
        <h2>Próximos programas</h2>
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
