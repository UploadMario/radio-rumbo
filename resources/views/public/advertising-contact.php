<?php
declare(strict_types=1);
use App\Helpers\UrlHelper;
$commercialWhatsapp = trim((string) ($station['whatsapp_publicidad'] ?? ''));
$whatsappDigits = preg_replace('/\D+/', '', $commercialWhatsapp) ?: '';
$email = trim((string) ($station['email'] ?? ''));
$phone = trim((string) ($station['phone'] ?? $station['telephone'] ?? ''));
$phoneHref = preg_replace('/[^0-9+]/', '', $phone) ?: '';
$facebook = UrlHelper::httpsOrEmpty((string) ($station['facebook_page_url'] ?? ''));
$maps = UrlHelper::httpsOrEmpty((string) ($station['maps_url'] ?? ''));
$address = trim((string) ($station['address'] ?? ''));
?>
<section class="page-hero"><div class="container split-section"><div><span class="section-kicker">Publicidad y contacto</span><h1>Hablemos de tu próxima campaña.</h1><p>Comunícate directamente con Radio Rumbo mediante nuestros canales institucionales.</p><div class="hero-actions"><?php if ($whatsappDigits !== ''): ?><a class="btn btn-primary" href="https://wa.me/<?= $e($whatsappDigits) ?>?text=Hola%2C%20quisiera%20consultar%20sobre%20publicidad%20en%20Radio%20Rumbo." target="_blank" rel="noopener noreferrer">Escribir por WhatsApp</a><?php endif; ?><a class="btn btn-soft" href="/en-vivo/">Escuchar en vivo</a></div></div><aside class="promo-panel presentation-promo"><h2>Atención directa</h2><p>Elige el canal que prefieras. No almacenamos mensajes ni conversaciones desde este sitio.</p></aside></div></section>
<section class="section-light" id="contacto"><div class="container card-grid"><?php if ($whatsappDigits !== ''): ?><article class="service-card"><h2>WhatsApp</h2><p><?= $e($commercialWhatsapp) ?></p><a class="btn btn-primary" href="https://wa.me/<?= $e($whatsappDigits) ?>" target="_blank" rel="noopener noreferrer">Abrir WhatsApp</a></article><?php endif; ?><?php if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)): ?><article class="service-card"><h2>Correo electrónico</h2><p><?= $e($email) ?></p><a class="btn btn-soft" href="mailto:<?= $e($email) ?>">Enviar correo</a></article><?php endif; ?><?php if ($facebook !== ''): ?><article class="service-card"><h2>Facebook</h2><p>Visita nuestra página oficial.</p><a class="btn btn-soft" href="<?= $e($facebook) ?>" target="_blank" rel="noopener noreferrer">Visitar Facebook</a></article><?php endif; ?><?php if ($address !== ''): ?><article class="service-card"><h2>Ubicación</h2><p><?= $e($address) ?></p><?php if ($maps !== ''): ?><a class="btn btn-soft" href="<?= $e($maps) ?>" target="_blank" rel="noopener noreferrer">Ver en mapas</a><?php endif; ?></article><?php endif; ?></div></section>
