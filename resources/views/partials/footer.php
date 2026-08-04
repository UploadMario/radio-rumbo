<?php

declare(strict_types=1);
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <div class="footer-brand">
                <img class="footer-logo" src="/assets/img/radio-rumbo-logo.png" alt="" width="64" height="64">
                <strong><?= $e((string) ($station['name'] ?? 'Radio Rumbo')) ?></strong>
            </div>
            <p><?= $e((string) ($station['tagline'] ?? 'La radio que se ve')) ?></p>
        </div>
        <div>
            <h2>Secciones</h2>
            <a href="/en-vivo/">En vivo</a>
            <a href="/programacion/">Programación</a>
            <a href="/noticias/">Noticias</a>
            <a href="/publicidad-contacto/">Publicidad</a>
        </div>
        <div>
            <h2>Contacto</h2>
            <p><?= $e((string) ($station['location'] ?? 'Huancayo, Perú')) ?></p>
            <p><?= $e((string) ($station['whatsapp_cabina'] ?? 'WhatsApp de cabina')) ?></p>
            <p><?= $e((string) ($station['whatsapp_publicidad'] ?? 'WhatsApp de publicidad')) ?></p>
            <p><?= $e((string) ($station['email'] ?? 'Correo institucional pendiente')) ?></p>
        </div>
    </div>
</footer>
