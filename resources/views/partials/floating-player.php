<?php

declare(strict_types=1);
?>
<aside class="floating-player" aria-label="Reproductor en vivo visual">
    <div class="player-station">
        <img src="/assets/img/radio-rumbo-mark.svg" alt="" width="38" height="38">
        <div>
            <span class="live-pill"><?= $e((string) ($currentProgram['status'] ?? 'EN VIVO')) ?></span>
            <strong><?= $e((string) ($currentProgram['name'] ?? 'Programa Matinal')) ?></strong>
            <small><?= $e((string) ($currentProgram['host'] ?? 'Conductor Principal')) ?> · <?= $e((string) ($currentProgram['schedule'] ?? 'Horario')) ?></small>
        </div>
    </div>
    <button class="player-play" type="button" data-player-toggle aria-label="Reproducir o pausar transmision visual">
        <span data-player-icon>▶</span>
    </button>
    <div class="audio-waves" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="player-actions">
        <button type="button" aria-label="Volumen visual">Vol</button>
        <button type="button" aria-label="Compartir visual">Compartir</button>
        <button type="button" aria-label="Expandir visual">Abrir</button>
    </div>
</aside>
