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
            <dl class="meta-row">
                <div><dt>Conductor</dt><dd><?= $e((string) ($currentProgram['host'] ?? 'Conductor principal')) ?></dd></div>
                <div><dt>Horario</dt><dd><?= $e((string) ($currentProgram['schedule'] ?? '06:00 - 09:00')) ?></dd></div>
                <div><dt>Stream</dt><dd><?= $audioUrl !== '' ? 'Audio configurado' : 'Pendiente de configurar' ?></dd></div>
            </dl>
        </div>
        <div class="big-player">
            <button class="big-play" type="button" data-player-toggle aria-label="Reproducir radio en vivo">▶</button>
            <?php if ($audioUrl !== ''): ?>
                <audio data-audio-player preload="none" controls src="<?= $e($audioUrl) ?>"></audio>
            <?php else: ?>
                <p class="embed-fallback">Audio en vivo pendiente de configuración.</p>
            <?php endif; ?>
            <div class="audio-waves large" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></div>
            <div class="hero-actions">
                <button class="btn btn-soft" type="button">Compartir</button>
                <button class="btn btn-primary" type="button"><?= $e((string) ($station['whatsapp_cabina'] ?? 'WhatsApp de cabina')) ?></button>
            </div>
        </div>
    </div>
</section>

<section class="container live-media-grid">
    <article class="media-card" id="video-en-vivo">
        <span class="section-kicker">La radio que se ve</span>
        <h2>Video en vivo</h2>
        <p>Transmisión visual preparada con widget externo seguro.</p>
        <?php if ($videoUrl !== ''): ?>
            <iframe
                src="<?= $e($videoUrl) ?>"
                title="Transmisión de video en vivo de Radio Rumbo"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allow="autoplay; fullscreen; picture-in-picture"></iframe>
            <a class="btn btn-primary" href="<?= $e($videoUrl) ?>" target="_blank" rel="noopener noreferrer">Abrir transmisión</a>
        <?php else: ?>
            <p class="embed-fallback">Video en vivo pendiente de configuración.</p>
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
