<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;
use App\Helpers\UrlHelper;
use App\Validators\ContactMessageValidator;

$services = is_array($mock['advertising_services'] ?? null) ? $mock['advertising_services'] : [];
$errors = is_array($contactErrors ?? null) ? $contactErrors : [];
$old = is_array($contactOld ?? null) ? $contactOld : [];
$success = is_string($contactSuccess ?? null) ? $contactSuccess : null;
$commercialWhatsapp = trim((string) ($station['whatsapp_publicidad'] ?? ''));
$hasCommercialWhatsapp = $commercialWhatsapp !== '' && stripos($commercialWhatsapp, 'WhatsApp de') === false;
$address = is_string($station['address'] ?? null) ? $station['address'] : 'Jr. Ayacucho 297, Huancayo, Junín, Perú';
$mapsUrl = UrlHelper::httpsOrEmpty(is_string($station['maps_url'] ?? null) ? $station['maps_url'] : '');
$socialLinks = is_array($station['social_links'] ?? null) ? $station['social_links'] : [];
$typeLabels = [
    'general' => 'Consulta general',
    'advertising' => 'Publicidad local',
    'news' => 'Información para prensa',
    'technical' => 'Soporte de señal',
];
?>
<section class="page-hero">
    <div class="container split-section">
        <div>
            <span class="section-kicker">Publicidad y contacto</span>
            <h1>Haz que tu negocio se escuche.</h1>
            <p>Conecta tu marca con Huancayo y la región Junín. Radio Rumbo ofrece alcance local, cercanía con la audiencia, segmentación por horarios y programas, flexibilidad para campañas comerciales y espacios publicitarios adaptados a distintos objetivos.</p>
            <div class="hero-actions">
                <?php if ($hasCommercialWhatsapp): ?>
                    <a class="btn btn-primary" href="https://wa.me/51<?= $e(preg_replace('/\D+/', '', $commercialWhatsapp) ?: $commercialWhatsapp) ?>" target="_blank" rel="noopener noreferrer">Consultar publicidad por WhatsApp</a>
                <?php endif; ?>
                <a class="btn btn-soft" href="#contacto">Enviar consulta</a>
                <a class="btn btn-soft" href="/en-vivo/">Ver transmisión</a>
            </div>
        </div>
        <aside class="promo-panel presentation-promo">
            <h2>Publicidad o contratos</h2>
            <p><?= $hasCommercialWhatsapp ? $e($commercialWhatsapp) : 'Escríbenos para coordinar tu campaña local.' ?></p>
            <small>Spots, menciones en vivo, campañas de temporada, promociones y coberturas locales.</small>
        </aside>
    </div>
</section>

<section class="container card-grid">
    <?php foreach ($services as $service): ?>
        <article class="service-card">
            <h2><?= $e((string) $service['title']) ?></h2>
            <p><?= $e((string) $service['summary']) ?></p>
        </article>
    <?php endforeach; ?>
</section>

<section class="section-light" id="contacto">
    <div class="container contact-grid">
        <form class="contact-form" action="/publicidad-contacto/#contacto" method="post">
            <h2>Contacto y publicidad</h2>
            <p>Déjanos tu consulta y nos pondremos en contacto contigo.</p>
            <?php if ($success !== null): ?><div class="admin-notice"><?= $e($success) ?></div><?php endif; ?>
            <?php if (isset($errors['_form'])): ?><div class="admin-error"><?= $e((string) $errors['_form']) ?></div><?php endif; ?>
            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
            <label class="sr-only">Sitio web<input type="text" name="website" value="" autocomplete="off" tabindex="-1"></label>
            <label>Nombre<input type="text" name="name" value="<?= $e((string) ($old['name'] ?? '')) ?>" placeholder="Nombre o empresa"><?php if (isset($errors['name'])): ?><small><?= $e((string) $errors['name']) ?></small><?php endif; ?></label>
            <label>Correo<input type="email" name="email" value="<?= $e((string) ($old['email'] ?? '')) ?>" placeholder="Correo de contacto"><?php if (isset($errors['email'])): ?><small><?= $e((string) $errors['email']) ?></small><?php endif; ?></label>
            <label>Teléfono<input type="text" name="phone" value="<?= $e((string) ($old['phone'] ?? '')) ?>" placeholder="WhatsApp o teléfono"><?php if (isset($errors['phone'])): ?><small><?= $e((string) $errors['phone']) ?></small><?php endif; ?></label>
            <label>Tipo de consulta
                <select name="type">
                    <?php foreach (ContactMessageValidator::TYPES as $type): ?>
                        <option value="<?= $e($type) ?>" <?= ($old['type'] ?? 'advertising') === $type ? 'selected' : '' ?>><?= $e($typeLabels[$type] ?? $type) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['type'])): ?><small><?= $e((string) $errors['type']) ?></small><?php endif; ?>
            </label>
            <label>Asunto<input type="text" name="subject" value="<?= $e((string) ($old['subject'] ?? '')) ?>"><?php if (isset($errors['subject'])): ?><small><?= $e((string) $errors['subject']) ?></small><?php endif; ?></label>
            <label>Mensaje<textarea name="message" rows="5" placeholder="Cuéntenos qué necesita"><?= $e((string) ($old['message'] ?? '')) ?></textarea><?php if (isset($errors['message'])): ?><small><?= $e((string) $errors['message']) ?></small><?php endif; ?></label>
            <button class="btn btn-primary" type="submit">Enviar mensaje</button>
        </form>
        <aside class="map-placeholder contact-info-panel">
            <span>Cabina Radio Rumbo</span>
            <strong>Jr. Ayacucho 297</strong>
            <p><?= $e($address) ?></p>
            <?php if ($mapsUrl !== ''): ?>
                <a class="btn btn-primary" href="<?= $e($mapsUrl) ?>" target="_blank" rel="noopener noreferrer">Ver ubicación en Google Maps</a>
            <?php endif; ?>
            <?php if ($socialLinks !== []): ?>
                <div class="social-links" aria-label="Redes oficiales">
                    <?php foreach ($socialLinks as $label => $url): ?>
                        <?php $safeUrl = UrlHelper::httpsOrEmpty(is_string($url) ? $url : ''); ?>
                        <?php if ($safeUrl !== ''): ?>
                            <a href="<?= $e($safeUrl) ?>" target="_blank" rel="noopener noreferrer"><?= $e((string) $label) ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</section>
