<?php

declare(strict_types=1);
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <div class="footer-brand">
                <img src="/assets/img/radio-rumbo-mark.svg" alt="" width="36" height="36">
                <strong><?= $e((string) ($station['name'] ?? 'Radio Rumbo')) ?></strong>
            </div>
            <p><?= $e((string) ($station['tagline'] ?? 'Radio local para todos.')) ?></p>
        </div>
        <div>
            <h2>Secciones</h2>
            <a href="/en-vivo/">En vivo</a>
            <a href="/programacion/">Programacion</a>
            <a href="/noticias/">Noticias</a>
            <a href="/publicidad-contacto/">Publicidad</a>
        </div>
        <div>
            <h2>Contacto</h2>
            <p><?= $e((string) ($station['location'] ?? 'Huancayo, Peru')) ?></p>
            <p><?= $e((string) ($station['whatsapp_cabina'] ?? 'WhatsApp de cabina')) ?></p>
            <p><?= $e((string) ($station['whatsapp_publicidad'] ?? 'WhatsApp de publicidad')) ?></p>
        </div>
    </div>
</footer>
