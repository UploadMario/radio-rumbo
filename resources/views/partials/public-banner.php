<?php

declare(strict_types=1);

$bannerPosition = is_string($bannerPosition ?? null) ? $bannerPosition : 'home_middle';
$banners = is_array($mock['active_banners'] ?? null) ? $mock['active_banners'] : [];
$filteredBanners = array_values(array_filter(
    $banners,
    static fn (array $banner): bool => (string) ($banner['position'] ?? '') === $bannerPosition
));
?>
<?php if ($filteredBanners !== []): ?>
    <section class="container public-banner-zone public-banner-<?= $e($bannerPosition) ?>">
        <?php foreach (array_slice($filteredBanners, 0, 2) as $banner): ?>
            <?php $imagePath = (string) ($banner['image_path'] ?? ''); ?>
            <article class="public-banner-card">
                <?php if ($imagePath !== ''): ?>
                    <img src="<?= $e($imagePath) ?>" alt="" loading="lazy">
                <?php else: ?>
                    <div class="banner-placeholder" aria-hidden="true">R</div>
                <?php endif; ?>
                <div>
                    <span class="section-kicker">Radio Rumbo</span>
                    <h2><?= $e((string) $banner['title']) ?></h2>
                    <?php if ((string) ($banner['subtitle'] ?? '') !== ''): ?>
                        <p><?= $e((string) $banner['subtitle']) ?></p>
                    <?php endif; ?>
                    <?php if ((string) ($banner['link_url'] ?? '') !== ''): ?>
                        <a class="btn btn-primary" href="<?= $e((string) $banner['link_url']) ?>" target="_blank" rel="noopener noreferrer">Ver mas</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
