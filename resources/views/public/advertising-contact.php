<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;
use App\Validators\ContactMessageValidator;

$services = is_array($mock['advertising_services'] ?? null) ? $mock['advertising_services'] : [];
$errors = is_array($contactErrors ?? null) ? $contactErrors : [];
$old = is_array($contactOld ?? null) ? $contactOld : [];
$success = is_string($contactSuccess ?? null) ? $contactSuccess : null;
$typeLabels = [
    'general' => 'Consulta general',
    'advertising' => 'Publicidad local',
    'news' => 'Informacion para prensa',
    'technical' => 'Consulta tecnica',
];
?>
<section class="page-hero">
    <div class="container split-section">
        <div>
            <span class="section-kicker">Publicidad y contacto</span>
            <h1>Anuncia con nosotros</h1>
            <p>Espacios para comercios, marcas y eventos locales de Huancayo. En esta fase el formulario guarda mensajes; no envia correos todavia.</p>
            <a class="btn btn-primary" href="#contacto">Solicitar informacion</a>
        </div>
        <aside class="promo-panel">
            <h2>WhatsApp comercial</h2>
            <p><?= $e((string) ($station['whatsapp_publicidad'] ?? 'WhatsApp de publicidad')) ?></p>
            <small>Placeholder hasta confirmar datos oficiales.</small>
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
            <?php if ($success !== null): ?><div class="admin-notice"><?= $e($success) ?></div><?php endif; ?>
            <?php if (isset($errors['_form'])): ?><div class="admin-error"><?= $e((string) $errors['_form']) ?></div><?php endif; ?>
            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
            <label class="sr-only">Sitio web<input type="text" name="website" value="" autocomplete="off" tabindex="-1"></label>
            <label>Nombre<input type="text" name="name" value="<?= $e((string) ($old['name'] ?? '')) ?>" placeholder="Nombre o empresa"><?php if (isset($errors['name'])): ?><small><?= $e((string) $errors['name']) ?></small><?php endif; ?></label>
            <label>Correo<input type="email" name="email" value="<?= $e((string) ($old['email'] ?? '')) ?>" placeholder="Correo institucional pendiente"><?php if (isset($errors['email'])): ?><small><?= $e((string) $errors['email']) ?></small><?php endif; ?></label>
            <label>Telefono<input type="text" name="phone" value="<?= $e((string) ($old['phone'] ?? '')) ?>" placeholder="WhatsApp de contacto"><?php if (isset($errors['phone'])): ?><small><?= $e((string) $errors['phone']) ?></small><?php endif; ?></label>
            <label>Tipo de consulta
                <select name="type">
                    <?php foreach (ContactMessageValidator::TYPES as $type): ?>
                        <option value="<?= $e($type) ?>" <?= ($old['type'] ?? 'advertising') === $type ? 'selected' : '' ?>><?= $e($typeLabels[$type] ?? $type) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['type'])): ?><small><?= $e((string) $errors['type']) ?></small><?php endif; ?>
            </label>
            <label>Asunto<input type="text" name="subject" value="<?= $e((string) ($old['subject'] ?? '')) ?>"><?php if (isset($errors['subject'])): ?><small><?= $e((string) $errors['subject']) ?></small><?php endif; ?></label>
            <label>Mensaje<textarea name="message" rows="5" placeholder="Cuentanos que necesitas"><?= $e((string) ($old['message'] ?? '')) ?></textarea><?php if (isset($errors['message'])): ?><small><?= $e((string) $errors['message']) ?></small><?php endif; ?></label>
            <button class="btn btn-primary" type="submit">Enviar mensaje</button>
        </form>
        <aside class="map-placeholder">
            <span>Ubicacion placeholder</span>
            <strong>Huancayo, Peru</strong>
            <p>Mapa, direccion exacta y redes sociales se conectaran cuando existan datos oficiales.</p>
        </aside>
    </div>
</section>
