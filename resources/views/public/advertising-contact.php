<?php

declare(strict_types=1);

$services = is_array($mock['advertising_services'] ?? null) ? $mock['advertising_services'] : [];
?>
<section class="page-hero">
    <div class="container split-section">
        <div>
            <span class="section-kicker">Publicidad y contacto</span>
            <h1>Anuncia con nosotros</h1>
            <p>Espacios visuales preparados para comercios, marcas y eventos locales. El formulario todavia no envia datos.</p>
            <a class="btn btn-primary" href="#contacto">Solicitar informacion</a>
        </div>
        <aside class="promo-panel">
            <h2>WhatsApp comercial</h2>
            <p><?= $e((string) ($station['whatsapp_publicidad'] ?? 'WhatsApp de publicidad')) ?></p>
            <small>Placeholder sin numero real.</small>
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
        <form class="contact-form" action="#" method="post">
            <h2>Formulario visual de contacto</h2>
            <label>Nombre<input type="text" placeholder="Nombre o empresa"></label>
            <label>Contacto<input type="text" placeholder="WhatsApp o correo placeholder"></label>
            <label>Mensaje<textarea rows="4" placeholder="Cuentanos que necesitas"></textarea></label>
            <button class="btn btn-primary" type="button">Enviar en futura fase</button>
        </form>
        <aside class="map-placeholder">
            <span>Ubicacion placeholder</span>
            <strong>Huancayo, Peru</strong>
            <p>Mapa y redes sociales se conectaran cuando existan datos oficiales.</p>
        </aside>
    </div>
</section>
