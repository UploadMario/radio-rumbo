<?php

declare(strict_types=1);

$schedule = is_array($mock['today_schedule'] ?? null) ? $mock['today_schedule'] : [];
$audioUrl = is_string($station['stream_audio_url'] ?? null) ? $station['stream_audio_url'] : '';
$facebookUrl = is_string($station['facebook_page_url'] ?? null) ? $station['facebook_page_url'] : '';
$facebookPluginUrl = is_string($station['facebook_page_plugin_url'] ?? null) ? $station['facebook_page_plugin_url'] : '';
?>
<section class="page-hero live-hero">
    <div class="container live-panel">
        <div class="live-copy">
            <img class="section-logo live-logo" src="/assets/img/radio-rumbo-logo.png" alt="Radio Rumbo" width="96" height="96">
            <span class="section-kicker">La radio que se ve</span>
            <h1>Radio Rumbo en vivo</h1>
            <p>Audio, video y noticias desde Huancayo para todos.</p>

            <div class="program-summary">
                <span>Programa actual</span>
                <strong><?= $e((string) ($currentProgram['name'] ?? 'Programa matinal')) ?></strong>
                <small><?= $e((string) ($currentProgram['schedule'] ?? '06:00 - 09:00')) ?> - <?= $e((string) ($currentProgram['host'] ?? 'Conductor principal')) ?></small>
            </div>

            <dl class="meta-row live-meta">
                <div><dt>Señal</dt><dd data-audio-status-label>En vivo</dd></div>
                <div><dt>Audio</dt><dd><?= $audioUrl !== '' ? 'Audio configurado' : 'Pendiente de configurar' ?></dd></div>
            </dl>

            <p class="audio-status-message" data-audio-status-message hidden>
                La transmisión de audio puede estar fuera de horario o pendiente de activación desde cabina.
            </p>

            <div class="hero-actions live-actions">
                <button class="btn btn-primary" type="button" data-player-toggle>Escuchar audio</button>
                <button class="btn btn-soft" type="button" data-audio-retry>Intentar nuevamente</button>
                <?php if ($audioUrl !== ''): ?>
                    <a class="btn btn-soft" href="<?= $e($audioUrl) ?>" target="_blank" rel="noopener noreferrer">Abrir transmisión oficial</a>
                <?php endif; ?>
                <?php if ($facebookUrl !== ''): ?>
                    <a class="btn btn-soft" href="<?= $e($facebookUrl) ?>" target="_blank" rel="noopener noreferrer">Ver Facebook</a>
                <?php endif; ?>
            </div>
        </div>
        <?php require dirname(__DIR__) . '/partials/live-video-player.php'; ?>
    </div>
</section>

<section class="container presentation-note live-note">
    <strong>Transmisión preparada</strong>
    <span>El video funciona de forma independiente al audio. Si la señal de audio no está activa, la web mantiene un estado profesional para el oyente.</span>
</section>

<section class="container live-media-grid">
    <article class="media-card" data-audio-status-root>
        <span class="section-kicker">Radio en vivo</span>
        <h2>Audio de Radio Rumbo</h2>
        <p>El audio permanece disponible en el reproductor flotante inferior y en este bloque secundario.</p>
        <strong class="audio-inline-status" data-audio-status-label>En vivo</strong>
        <?php if ($audioUrl !== ''): ?>
            <audio data-audio-player preload="none" controls src="<?= $e($audioUrl) ?>"></audio>
            <p class="audio-status-message" data-audio-status-message hidden>
                La transmisión de audio no está disponible en este momento. Puede que la radio esté fuera de horario o que la señal aún no haya sido enviada desde cabina.
            </p>
            <div class="audio-fallback-actions">
                <button class="btn btn-primary" type="button" data-audio-retry>Intentar nuevamente</button>
                <a class="btn btn-soft admin-soft" href="<?= $e($audioUrl) ?>" target="_blank" rel="noopener noreferrer">Abrir transmisión oficial</a>
            </div>
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
        <p>Programación referencial pendiente de confirmación final por Radio Rumbo.</p>
    </div>
    <div class="schedule-list">
        <?php foreach (array_slice($schedule, 1) as $program): ?>
            <article>
                <time><?= $e((string) $program['time']) ?></time>
                <div>
                    <h3><?= $e((string) $program['name']) ?></h3>
                    <p><?= $e((string) $program['host']) ?> - <?= $e((string) $program['category']) ?></p>
                </div>
                <a class="btn btn-soft admin-soft" href="/programacion/">Ver programación</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
