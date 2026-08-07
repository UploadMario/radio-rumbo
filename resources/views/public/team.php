<?php

declare(strict_types=1);

$teamMembers = is_array($teamMembers ?? null) ? $teamMembers : [];
?>
<section class="page-title container">
    <span class="section-kicker">Voces de la radio</span>
    <h1>Nuestro equipo</h1>
    <p>Sección preparada para cargar el equipo oficial de Radio Rumbo.</p>
</section>
<section class="container presentation-note light-note">
    <strong>Contenido pendiente de confirmación</strong>
    <span>Los nombres, programas y biografías finales se completarán con información oficial de la radio.</span>
</section>
<section class="container team-grid">
    <?php if ($teamMembers === []): ?>
        <article class="person-card large presentation-empty">
            <div class="avatar-placeholder">R</div>
            <h2>Equipo oficial pendiente</h2>
            <p>Esta sección está lista para publicar los integrantes activos de Radio Rumbo.</p>
        </article>
    <?php endif; ?>
    <?php foreach ($teamMembers as $member): ?>
        <article class="person-card large">
            <?php if ((string) ($member['photo_path'] ?? '') !== ''): ?>
                <img class="avatar-placeholder" src="<?= $e((string) $member['photo_path']) ?>" alt="" width="82" height="82">
            <?php else: ?>
                <div class="avatar-placeholder"><?= $e(strtoupper(substr((string) $member['name'], 0, 1))) ?></div>
            <?php endif; ?>
            <h2><?= $e((string) $member['name']) ?></h2>
            <p><?= $e((string) $member['role']) ?></p>
            <span><?= $e((string) ($member['program_name'] ?: 'Radio Rumbo')) ?></span>
            <?php if ((string) ($member['bio'] ?? '') !== ''): ?><p><?= $e((string) $member['bio']) ?></p><?php endif; ?>
            <div class="hero-actions">
                <?php if ((string) ($member['facebook_url'] ?? '') !== ''): ?><a class="btn btn-soft admin-soft" href="<?= $e((string) $member['facebook_url']) ?>" target="_blank" rel="noopener noreferrer">Facebook</a><?php endif; ?>
                <?php if ((string) ($member['instagram_url'] ?? '') !== ''): ?><a class="btn btn-soft admin-soft" href="<?= $e((string) $member['instagram_url']) ?>" target="_blank" rel="noopener noreferrer">Instagram</a><?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</section>
