<?php

declare(strict_types=1);

$audioUrl = is_string($station['stream_audio_url'] ?? null) ? $station['stream_audio_url'] : '';
$nextProgram = is_array($mock['next_program'] ?? null) ? $mock['next_program'] : null;
?>
<aside
    class="floating-player"
    aria-label="Reproductor en vivo"
    data-audio-status-root
    data-program-name="<?= $e((string) ($currentProgram['name'] ?? 'Programación en pausa')) ?>"
    data-program-host="<?= $e((string) ($currentProgram['host'] ?? 'Radio Rumbo')) ?>"
    data-program-schedule="<?= $e((string) ($currentProgram['schedule'] ?? 'Horario por confirmar')) ?>"
>
    <div class="player-station" aria-label="Programa en vivo">
        <img class="player-logo" src="/assets/img/radio-rumbo-logo.png" alt="" width="52" height="52">
        <div class="player-copy">
            <span class="player-live-badge" data-audio-status-label>En vivo</span>
            <strong data-player-program><?= $e((string) ($currentProgram['name'] ?? 'Programación en pausa')) ?></strong>
            <small><span data-player-host><?= $e((string) ($currentProgram['host'] ?? 'Radio Rumbo')) ?></span> - <span data-player-schedule><?= $e((string) ($currentProgram['schedule'] ?? 'Horario por confirmar')) ?></span></small>
            <?php if ($nextProgram !== null): ?>
                <small class="player-next">Luego: <?= $e((string) ($nextProgram['name'] ?? 'Próximo bloque')) ?></small>
            <?php endif; ?>
        </div>
    </div>

    <div class="player-center" aria-label="Controles principales">
        <button class="player-play" type="button" data-player-toggle aria-label="Reproducir o pausar Radio Rumbo en vivo">
            <span data-player-icon>▶</span>
        </button>
        <div class="audio-waves" aria-hidden="true">
            <span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>

    <?php if ($audioUrl !== ''): ?>
        <audio data-audio-player preload="none" src="<?= $e($audioUrl) ?>"></audio>
    <?php endif; ?>

    <div class="player-actions" aria-label="Controles secundarios">
        <div class="volume-control">
            <button type="button" class="volume-toggle" data-volume-toggle aria-expanded="false" aria-label="Ajustar volumen">
                <span aria-hidden="true">Volumen</span>
            </button>
            <label class="volume-slider-wrap">
                <span class="sr-only">Volumen</span>
                <input type="range" min="0" max="1" step="0.05" value="0.8" data-volume-slider>
            </label>
        </div>
        <button class="player-retry" type="button" data-audio-retry hidden>Reintentar</button>
        <a class="player-transmission-link" href="/en-vivo/">Ver transmisión</a>
    </div>
</aside>
