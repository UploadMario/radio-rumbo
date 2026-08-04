<?php

declare(strict_types=1);

$teamMembers = is_array($teamMembers ?? null) ? $teamMembers : [];
?>
<section class="page-title container">
    <span class="section-kicker">Personas detras de la señal</span>
    <h1>Nuestro equipo</h1>
    <p>Integrantes activos cargados desde el panel administrativo, sin uploads reales en esta fase.</p>
</section>
<section class="container team-grid">
    <?php if ($teamMembers === []): ?>
        <article class="person-card large">
            <div class="avatar-placeholder">R</div>
            <h2>El equipo de Radio Rumbo estara disponible proximamente.</h2>
            <p>Modulo listo para conectar perfiles oficiales.</p>
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
                <?php if ((string) ($member['facebook_url'] ?? '') !== ''): ?><a class="btn btn-soft" href="<?= $e((string) $member['facebook_url']) ?>" target="_blank" rel="noopener noreferrer">Facebook</a><?php endif; ?>
                <?php if ((string) ($member['instagram_url'] ?? '') !== ''): ?><a class="btn btn-soft" href="<?= $e((string) $member['instagram_url']) ?>" target="_blank" rel="noopener noreferrer">Instagram</a><?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</section>
