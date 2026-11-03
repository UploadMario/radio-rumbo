<?php

declare(strict_types=1);

use App\Helpers\UrlHelper;

$videoUrl = UrlHelper::httpsOrEmpty(is_string($videoUrl ?? null) ? $videoUrl : '');
$videoTitle = is_string($videoTitle ?? null) ? $videoTitle : 'Transmisión en vivo';
$videoDescription = is_string($videoDescription ?? null)
    ? $videoDescription
    : 'Cabina y programas especiales de Radio Rumbo.';
$videoLoading = ($videoLoading ?? 'eager') === 'lazy' ? 'lazy' : 'eager';
$videoCardClass = is_string($videoCardClass ?? null) ? $videoCardClass : '';
?>
<article class="live-video-card <?= $e($videoCardClass) ?>" id="video-en-vivo">
    <div class="live-video-card__header">
        <span class="live-status-badge"><span aria-hidden="true">●</span> EN VIVO</span>
        <div>
            <h2><?= $e($videoTitle) ?></h2>
            <p><?= $e($videoDescription) ?></p>
        </div>
    </div>

    <?php if ($videoUrl !== ''): ?>
        <div class="live-video-shell" data-embed-shell>
            <iframe
                class="live-video-frame"
                data-external-embed
                src="<?= $e($videoUrl) ?>"
                title="Transmisión de video en vivo de Radio Rumbo"
                loading="<?= $e($videoLoading) ?>"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="live-video-lower-third" aria-label="Información de transmisión">
            <div>
                <span>Ahora en cabina</span>
                <strong><?= $e((string) ($currentProgram['name'] ?? 'Radio Rumbo en vivo')) ?></strong>
                <small><?= $e((string) ($currentProgram['schedule'] ?? 'Señal online')) ?></small>
            </div>
            <div class="signal-line" aria-hidden="true">
                <i></i><i></i><i></i><i></i>
            </div>
        </div>
    <?php else: ?>
        <p class="embed-fallback">La transmisión de video no está disponible por el momento.</p>
    <?php endif; ?>
</article>
