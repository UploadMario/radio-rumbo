<?php

declare(strict_types=1);

use App\Helpers\UrlHelper;

$videoUrl = UrlHelper::httpsOrEmpty(is_string($videoUrl ?? null) ? $videoUrl : '');
?>
<article class="live-video-card">
    <div class="live-video-card__header">
        <span class="live-pill">EN VIVO</span>
        <div>
            <h2>Transmisión en vivo</h2>
            <p>Mira también la transmisión en vivo de cabina y programas especiales.</p>
        </div>
    </div>

    <?php if ($videoUrl !== ''): ?>
        <iframe
            class="live-video-frame"
            src="<?= $e($videoUrl) ?>"
            title="Transmisión de video en vivo de Radio Rumbo"
            loading="eager"
            allow="autoplay; fullscreen; picture-in-picture"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        <a class="btn btn-primary" href="<?= $e($videoUrl) ?>" target="_blank" rel="noopener noreferrer">
            Abrir transmisión
        </a>
    <?php else: ?>
        <p class="embed-fallback">La transmisión de video está pendiente de configuración.</p>
    <?php endif; ?>
</article>
