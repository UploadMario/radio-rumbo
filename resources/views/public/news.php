<?php

declare(strict_types=1);

$items = is_array($newsItems ?? null) ? $newsItems : [];
$message = is_string($newsLoadMessage ?? null) ? $newsLoadMessage : null;
$facebookUrl = is_string($station['facebook_page_url'] ?? null) ? $station['facebook_page_url'] : '';
?>
<section class="section-light news-page">
    <div class="container page-title">
        <span class="section-kicker">Actualidad</span>
        <h1>Noticias</h1>
        <p>Las publicaciones recientes de Radio Rumbo se muestran directamente desde su página oficial de Facebook para mantener la información actualizada.</p>
    </div>

    <?php if ($message !== null): ?>
        <div class="container empty-state"><?= $e($message) ?></div>
    <?php endif; ?>

    <div class="container">
        <?php
        $facebookPageUrl = $facebookUrl;
        $facebookPluginUrl = is_string($station['facebook_page_plugin_url'] ?? null)
            ? $station['facebook_page_plugin_url']
            : '';
        $facebookTitle = 'Publicaciones recientes';
        $facebookDescription = 'Noticias locales, regionales y nacionales publicadas por Radio Rumbo.';
        $facebookContext = 'noticias';
        require dirname(__DIR__) . '/partials/facebook-feed.php';
        ?>
    </div>

    <?php if ($items !== []): ?>
        <div class="container section-heading compact-heading">
            <span class="section-kicker">Cobertura radial</span>
            <h2>Información que acompaña la señal</h2>
            <p>Radio Rumbo cubre coyuntura política y social del Valle del Mantaro, junto a contenidos de servicio para sus oyentes.</p>
        </div>
        <div class="container card-grid">
            <?php foreach (array_slice($items, 0, 3) as $item): ?>
                <article class="news-card">
                    <span><?= $e((string) ($item['category'] ?? 'Radio Rumbo')) ?></span>
                    <h3><?= $e((string) ($item['title'] ?? 'Información local')) ?></h3>
                    <p><?= $e((string) ($item['summary'] ?? '')) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
