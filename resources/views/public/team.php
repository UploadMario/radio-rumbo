<?php

declare(strict_types=1);

$teamMembers = is_array($teamMembers ?? null) ? $teamMembers : [];
$initial = static function (string $name): string {
    if (function_exists('mb_substr')) {
        return mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8');
    }

    return strtoupper(substr($name, 0, 1));
};
$publicBio = static function (string $bio): string {
    $bio = str_ireplace('placeholder', 'de Radio Rumbo', $bio);
    $bio = str_ireplace('sin datos personales reales', 'para consultas de la audiencia', $bio);
    $bio = str_ireplace('para reemplazar con datos oficiales', 'para compartir con la audiencia', $bio);

    return $bio;
};
?>
<section class="page-title container">
    <span class="section-kicker">Voces de la radio</span>
    <h1>Nuestro equipo</h1>
    <p>Conoce a las voces y áreas que hacen posible la señal de Radio Rumbo.</p>
</section>
<section class="container team-grid">
    <?php if ($teamMembers === []): ?>
        <article class="person-card large presentation-empty">
            <div class="avatar-placeholder">R</div>
            <h2>Equipo Radio Rumbo</h2>
            <p>La señal se sostiene desde cabina, prensa, producción y área comercial de Radio Rumbo.</p>
        </article>
    <?php endif; ?>
    <?php foreach ($teamMembers as $member): ?>
        <article class="person-card large">
            <?php if ((string) ($member['photo_path'] ?? '') !== ''): ?>
                <img class="avatar-placeholder" src="<?= $e((string) $member['photo_path']) ?>" alt="<?= $e('Foto de ' . (string) $member['name']) ?>" width="82" height="82" loading="lazy">
            <?php else: ?>
                <div class="avatar-placeholder"><?= $e($initial((string) $member['name'])) ?></div>
            <?php endif; ?>
            <h2><?= $e((string) $member['name']) ?></h2>
            <p><?= $e((string) $member['role']) ?></p>
            <span><?= $e((string) ($member['program_name'] ?: 'Radio Rumbo')) ?></span>
            <?php if ((string) ($member['bio'] ?? '') !== ''): ?><p><?= $e($publicBio((string) $member['bio'])) ?></p><?php endif; ?>
            <div class="hero-actions">
                <?php if ((string) ($member['facebook_url'] ?? '') !== ''): ?><a class="btn btn-soft admin-soft" href="<?= $e((string) $member['facebook_url']) ?>" target="_blank" rel="noopener noreferrer">Facebook</a><?php endif; ?>
                <?php if ((string) ($member['instagram_url'] ?? '') !== ''): ?><a class="btn btn-soft admin-soft" href="<?= $e((string) $member['instagram_url']) ?>" target="_blank" rel="noopener noreferrer">Instagram</a><?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</section>
