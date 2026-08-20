<?php

declare(strict_types=1);

use App\Helpers\UrlHelper;

$cabina = trim((string) ($station['whatsapp_cabina'] ?? ''));
$publicidad = trim((string) ($station['whatsapp_publicidad'] ?? ''));
$email = trim((string) ($station['email'] ?? ''));
$address = trim((string) ($station['address'] ?? ''));
$socialLinks = is_array($station['social_links'] ?? null) ? $station['social_links'] : [];
$hasCabina = $cabina !== '' && stripos($cabina, 'WhatsApp de') === false;
$hasPublicidad = $publicidad !== '' && stripos($publicidad, 'WhatsApp de') === false;
$hasEmail = $email !== '' && stripos($email, 'pendiente') === false;
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <div class="footer-brand">
                <img class="footer-logo" src="/assets/img/radio-rumbo-logo.png" alt="Radio Rumbo" width="64" height="64">
            </div>
            <p><?= $e((string) ($station['tagline'] ?? 'La radio que se ve')) ?></p>
            <p class="footer-note">91.1 FM, 1560 AM, señal online y video digital desde Huancayo para el Valle del Mantaro.</p>
        </div>
        <div>
            <h2>Secciones</h2>
            <a href="/en-vivo/">En vivo</a>
            <a href="/programacion/">Programación</a>
            <a href="/noticias/">Noticias</a>
            <a href="/equipo/">Equipo</a>
            <a href="/publicidad-contacto/">Publicidad y contacto</a>
        </div>
        <div>
            <h2>Contacto</h2>
            <p><?= $e($address !== '' ? $address : (string) ($station['location'] ?? 'Huancayo, Junín, Perú')) ?></p>
            <?php if ($hasCabina): ?><p><?= $e($cabina) ?></p><?php endif; ?>
            <?php if ($hasPublicidad): ?><p>Publicidad o contratos: <?= $e($publicidad) ?></p><?php endif; ?>
            <?php if ($hasEmail): ?><p><?= $e($email) ?></p><?php endif; ?>
            <a href="/publicidad-contacto/">Publicidad y contacto</a>
            <?php if ($socialLinks !== []): ?>
                <div class="footer-social-links" aria-label="Redes oficiales">
                    <?php foreach ($socialLinks as $label => $url): ?>
                        <?php $safeUrl = UrlHelper::httpsOrEmpty(is_string($url) ? $url : ''); ?>
                        <?php if ($safeUrl !== ''): ?>
                            <a href="<?= $e($safeUrl) ?>" target="_blank" rel="noopener noreferrer"><?= $e((string) $label) ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>
