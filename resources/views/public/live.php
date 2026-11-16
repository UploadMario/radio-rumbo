<?php

declare(strict_types=1);

$schedule = is_array($mock['today_schedule'] ?? null) ? $mock['today_schedule'] : [];
$audioUrl = is_string($station['stream_audio_url'] ?? null) ? $station['stream_audio_url'] : '';
$videoUrl = is_string($station['live_video_widget_url'] ?? null) ? $station['live_video_widget_url'] : '';
$facebookUrl = is_string($station['facebook_page_url'] ?? null) ? $station['facebook_page_url'] : '';
$facebookPluginUrl = is_string($station['facebook_page_plugin_url'] ?? null) ? $station['facebook_page_plugin_url'] : '';
$frequencyFm = is_string($station['frequency_fm'] ?? null) ? $station['frequency_fm'] : '91.1 FM';
$frequencyAm = is_string($station['frequency_am'] ?? null) ? $station['frequency_am'] : '1560 AM';
$coverage = is_string($station['coverage'] ?? null) ? $station['coverage'] : 'Valle del Mantaro';
?>
<section class="page-hero live-hero">
    <div class="container live-panel">
        <div class="live-copy">
            <span class="section-kicker">La radio que se ve</span>
            <h1>Radio Rumbo en vivo</h1>
            <p>Medio multiplataforma con radio, señal online, video digital y redes desde Huancayo.</p>

            <div class="program-summary">
                <span>Programa actual</span>
                <strong><?= $e((string) ($currentProgram['name'] ?? 'Sin programa registrado')) ?></strong>
                <small><?= $e((string) ($currentProgram['schedule'] ?? 'Horario no disponible')) ?><?= ($currentProgram['host'] ?? '') !== '' ? ' - ' . $e((string) $currentProgram['host']) : '' ?></small>
            </div>

            <dl class="meta-row live-meta">
                <div><dt>FM</dt><dd><?= $e($frequencyFm) ?></dd></div>
                <div><dt>AM</dt><dd><?= $e($frequencyAm) ?></dd></div>
                <div><dt>Online</dt><dd data-audio-status-label>Señal disponible</dd></div>
                <div><dt>Cobertura</dt><dd><?= $e($coverage) ?></dd></div>
            </dl>

            <p class="audio-status-message" data-audio-status-message hidden>
                La transmisión de audio puede estar fuera de horario o temporalmente sin señal desde cabina.
            </p>

            <div class="hero-actions live-actions">
                <button class="btn btn-primary" type="button" data-player-toggle>Escuchar audio</button>
                <button class="btn btn-soft" type="button" data-audio-retry hidden>Reintentar audio</button>
                <?php if ($facebookUrl !== ''): ?>
                    <a class="btn btn-soft" href="#facebook-en-vivo">Ver Facebook</a>
                <?php endif; ?>
            </div>
        </div>
        <?php
        $videoTitle = 'Transmisión en vivo';
        $videoDescription = 'Cabina, entrevistas y programas especiales desde Huancayo.';
        $videoLoading = 'eager';
        $videoCardClass = 'live-video-card--featured';
        require dirname(__DIR__) . '/partials/live-video-player.php';
        ?>
    </div>
</section>

<section class="container live-media-grid">
    <article class="media-card" data-audio-status-root>
        <span class="section-kicker">Radio en vivo</span>
        <h2>Audio de Radio Rumbo</h2>
        <p>Escucha <?= $e($frequencyFm) ?>, <?= $e($frequencyAm) ?> y la señal online desde el reproductor inferior mientras navegas por la web.</p>
        <strong class="audio-inline-status" data-audio-status-label>Señal disponible</strong>
        <?php if ($audioUrl !== ''): ?>
            <p class="audio-status-message" data-audio-status-message hidden>
                La transmisión de audio no está disponible en este momento. Puede que la radio esté fuera de horario o temporalmente sin señal desde cabina.
            </p>
            <div class="audio-fallback-actions">
                <button class="btn btn-primary" type="button" data-player-toggle>Escuchar en vivo</button>
                <button class="btn btn-soft admin-soft" type="button" data-audio-retry hidden>Reintentar audio</button>
            </div>
        <?php else: ?>
            <p class="embed-fallback">La señal de audio no está disponible por el momento.</p>
        <?php endif; ?>
    </article>
    <div id="facebook-en-vivo">
        <?php
        $facebookPageUrl = $facebookUrl;
        $facebookTitle = 'También en Facebook';
        $facebookDescription = 'Últimas publicaciones de Radio Rumbo Huancayo.';
        $facebookContext = 'en-vivo';
        require dirname(__DIR__) . '/partials/facebook-feed.php';
        ?>
    </div>
</section>

<section class="container">
    <div class="section-heading">
        <div>
            <span class="section-kicker">Sigue la señal</span>
            <h2>Próximos programas</h2>
            <p>Más voces, música e información durante el día.</p>
        </div>
        <a class="btn btn-soft" href="/programacion/">Ver programación completa</a>
    </div>
    <div class="schedule-list">
        <?php foreach (array_slice($schedule, 1) as $program): ?>
            <article>
                <time><?= $e((string) $program['time']) ?></time>
                <div>
                    <h3><?= $e((string) $program['name']) ?></h3>
                    <p><?= $e((string) $program['host']) ?> - <?= $e((string) $program['category']) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
