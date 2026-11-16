<?php

declare(strict_types=1);

use App\Helpers\UrlHelper;

$audioUrl = is_string($station['stream_audio_url'] ?? null) ? $station['stream_audio_url'] : '';
$videoUrl = UrlHelper::httpsOrEmpty(is_string($station['live_video_widget_url'] ?? null) ? $station['live_video_widget_url'] : '');
$nextProgram = is_array($mock['next_program'] ?? null) ? $mock['next_program'] : null;
$programName = (string) ($currentProgram['name'] ?? 'Sin programa registrado');
$programHost = (string) ($currentProgram['host'] ?? 'Radio Rumbo');
$programSchedule = (string) ($currentProgram['schedule'] ?? 'Horario por confirmar');
?>
<aside
    class="floating-player"
    aria-label="Reproductor en vivo"
    data-audio-status-root
    data-program-name="<?= $e($programName) ?>"
    data-program-host="<?= $e($programHost) ?>"
    data-program-schedule="<?= $e($programSchedule) ?>"
>
    <div class="player-station" aria-label="Programa en vivo">
        <img class="player-logo" src="/assets/img/radio-rumbo-isotype-web.png" alt="" width="52" height="52">
        <div class="player-copy" aria-live="polite">
            <span class="player-live-badge" data-audio-status-label aria-live="polite">Señal disponible</span>
            <strong data-player-program><?= $e($programName) ?></strong>
            <small><span data-player-host><?= $e($programHost) ?></span> - <span data-player-schedule><?= $e($programSchedule) ?></span></small>
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
            <button type="button" class="volume-toggle" data-volume-mute aria-pressed="false" aria-label="Silenciar audio">
                <svg data-volume-icon aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" focusable="false">
                    <path class="volume-icon-speaker" d="M4 9v6h4l5 4V5L8 9H4Z"></path>
                    <path class="volume-icon-wave" d="M16 8.5a5 5 0 0 1 0 7M18.5 6a8.5 8.5 0 0 1 0 12"></path>
                    <path class="volume-icon-muted" d="M16 9l5 5m0-5-5 5"></path>
                </svg>
            </button>
            <label class="volume-slider-wrap">
                <span class="sr-only">Volumen</span>
                <input
                    type="range"
                    min="0"
                    max="1"
                    step="0.05"
                    value="0.8"
                    data-volume-slider
                    aria-label="Volumen del audio en vivo"
                    aria-valuemin="0"
                    aria-valuemax="1"
                    aria-valuenow="0.8"
                >
            </label>
        </div>
        <button class="player-retry" type="button" data-audio-retry hidden>Reintentar</button>
        <?php if ($videoUrl !== ''): ?>
            <button class="player-video-toggle" type="button" data-video-dock-toggle aria-expanded="false" aria-controls="mini-live-video">Ver video en vivo</button>
        <?php else: ?>
            <a class="player-transmission-link" href="/en-vivo/">Ver video en vivo</a>
        <?php endif; ?>
    </div>
</aside>

<?php if ($videoUrl !== ''): ?>
    <aside class="mini-live-video" id="mini-live-video" aria-label="Video en vivo de Radio Rumbo" hidden>
        <div class="mini-live-video__header">
            <div>
                <span class="live-status-badge"><span aria-hidden="true">●</span> EN VIVO</span>
                <strong>Cabina en vivo</strong>
            </div>
            <button type="button" data-video-dock-close aria-label="Cerrar video en vivo">Cerrar</button>
        </div>
        <div class="mini-live-video__frame">
            <iframe
                data-video-dock-frame
                data-src="<?= $e($videoUrl) ?>"
                title="Transmisión de video en vivo de Radio Rumbo"
                loading="lazy"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <a href="/en-vivo/">Abrir página En vivo</a>
    </aside>
<?php endif; ?>
