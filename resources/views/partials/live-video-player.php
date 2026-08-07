<?php

declare(strict_types=1);

use App\Helpers\UrlHelper;

$videoUrl = UrlHelper::httpsOrEmpty(is_string($videoUrl ?? null) ? $videoUrl : '');
?>
<article class="live-video-card" id="video-en-vivo">
    <div class="live-video-card__header">
        <span class="live-status-badge"><span aria-hidden="true">●</span> EN VIVO</span>
        <div>
            <h2>Transmisión en vivo</h2>
            <p>Cabina y programas especiales de Radio Rumbo.</p>
        </div>
    </div>

    <?php if ($videoUrl !== ''): ?>
        <div class="live-video-shell">
            <iframe
                class="live-video-frame"
                src="<?= $e($videoUrl) ?>"
                title="Transmisión de video en vivo de Radio Rumbo"
                loading="eager"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <a class="btn btn-soft live-video-action" href="<?= $e($videoUrl) ?>" target="_blank" rel="noopener noreferrer">
            Abrir transmisión
        </a>
    <?php else: ?>
        <p class="embed-fallback">La transmisión de video está pendiente de configuración.</p>
    <?php endif; ?>
</article>
