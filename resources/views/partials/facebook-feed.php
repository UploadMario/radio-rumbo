<?php

declare(strict_types=1);

use App\Helpers\UrlHelper;

$facebookPageUrl = UrlHelper::httpsOrEmpty(is_string($facebookPageUrl ?? null) ? $facebookPageUrl : '');
$facebookPluginUrl = UrlHelper::httpsOrEmpty(is_string($facebookPluginUrl ?? null) ? $facebookPluginUrl : '');
$facebookTitle = is_string($facebookTitle ?? null) ? $facebookTitle : 'Noticias en Facebook';
$facebookDescription = is_string($facebookDescription ?? null)
    ? $facebookDescription
    : 'Publicaciones recientes de Radio Rumbo Huancayo.';
$facebookContext = is_string($facebookContext ?? null) ? $facebookContext : 'publico';

if ($facebookPluginUrl === '' && $facebookPageUrl !== '') {
    $facebookPluginUrl = UrlHelper::facebookPagePluginUrl($facebookPageUrl);
}
?>
<article class="facebook-news-panel facebook-news-panel--<?= $e($facebookContext) ?>">
    <div class="facebook-news-header">
        <div>
            <span class="section-kicker">Facebook oficial</span>
            <h2><?= $e($facebookTitle) ?></h2>
            <p><?= $e($facebookDescription) ?></p>
        </div>
        <?php if ($facebookPageUrl !== ''): ?>
            <a class="btn btn-soft admin-soft" href="<?= $e($facebookPageUrl) ?>" target="_blank" rel="noopener noreferrer">Ver Facebook oficial</a>
        <?php endif; ?>
    </div>

    <?php if ($facebookPluginUrl !== ''): ?>
        <div class="facebook-embed-shell">
            <iframe
                class="facebook-embed-frame"
                title="Publicaciones de Facebook de Radio Rumbo"
                src="<?= $e($facebookPluginUrl) ?>"
                width="500"
                height="650"
                style="border:none;overflow:hidden"
                scrolling="no"
                frameborder="0"
                allowfullscreen="true"
                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <p class="embed-fallback">Si las publicaciones no se visualizan, puedes revisar la pagina oficial de Radio Rumbo en Facebook.</p>
    <?php else: ?>
        <div class="facebook-fallback">
            <strong>Publicaciones no disponibles en este momento</strong>
            <span>Las publicaciones de Facebook estaran disponibles cuando la integracion externa permita su visualizacion. Tambien puedes revisar la pagina oficial de Radio Rumbo.</span>
        </div>
    <?php endif; ?>

    <?php if ($facebookPageUrl !== '' && $facebookPluginUrl === ''): ?>
        <a class="btn btn-primary" href="<?= $e($facebookPageUrl) ?>" target="_blank" rel="noopener noreferrer">Ver Facebook oficial</a>
    <?php endif; ?>
</article>
