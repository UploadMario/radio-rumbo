<?php

declare(strict_types=1);

$audioUrl = is_string($station['stream_audio_url'] ?? null) ? $station['stream_audio_url'] : '';
?>
<aside class="floating-player" aria-label="Reproductor en vivo">
    <div class="player-station">
        <img class="player-logo" src="/assets/img/radio-rumbo-logo.png" alt="" width="46" height="46">
        <div>
            <span class="live-pill"><?= $e((string) ($currentProgram['status'] ?? 'EN VIVO')) ?></span>
            <strong><?= $e((string) ($currentProgram['name'] ?? 'Programa matinal')) ?></strong>
            <small><?= $e((string) ($currentProgram['host'] ?? 'Conductor principal')) ?> · <?= $e((string) ($currentProgram['schedule'] ?? 'Horario')) ?></small>
        </div>
    </div>
    <button class="player-play" type="button" data-player-toggle aria-label="Reproducir o pausar radio en vivo">
        <span data-player-icon>▶</span>
    </button>
    <?php if ($audioUrl !== ''): ?>
        <audio data-audio-player preload="none" src="<?= $e($audioUrl) ?>"></audio>
    <?php endif; ?>
    <div class="audio-waves" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="player-actions">
        <button type="button" aria-label="Volumen visual">Volumen</button>
        <button type="button" aria-label="Compartir visual">Compartir</button>
        <a href="/en-vivo/" aria-label="Abrir transmisión en vivo">Abrir</a>
    </div>
</aside>
