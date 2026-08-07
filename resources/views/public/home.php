<?php

declare(strict_types=1);

$schedule = is_array($mock['today_schedule'] ?? null) ? $mock['today_schedule'] : [];
$news = is_array($mock['news'] ?? null) ? $mock['news'] : [];
$team = is_array($mock['team'] ?? null) ? $mock['team'] : [];
$plannedModules = is_array($mock['planned_modules'] ?? null) ? $mock['planned_modules'] : [];
$facebookUrl = is_string($station['facebook_page_url'] ?? null) ? $station['facebook_page_url'] : '';
?>
<section class="hero-section">
    <img class="hero-watermark" src="/assets/img/radio-rumbo-logo.png" alt="" aria-hidden="true">
    <div class="container hero-grid">
        <div class="hero-copy">
            <img class="hero-logo" src="/assets/img/radio-rumbo-logo.png" alt="Radio Rumbo" width="156" height="156">
            <span class="section-kicker">Huancayo, Perú</span>
            <h1>Radio Rumbo: La radio que se ve.</h1>
            <p>Nueva plataforma web moderna, administrable y lista para completar con información oficial antes del despliegue final.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="/en-vivo/">Escuchar en vivo</a>
                <a class="btn btn-soft" href="/en-vivo/#video-en-vivo">Ver transmisión</a>
                <a class="btn btn-soft" href="/publicidad-contacto/">Escribir a cabina</a>
            </div>
        </div>
        <article class="live-card">
            <span class="live-pill"><?= $e((string) ($currentProgram['status'] ?? 'EN VIVO')) ?></span>
            <h2>En vivo ahora</h2>
            <p class="program-name"><?= $e((string) ($currentProgram['name'] ?? 'Programa matinal')) ?></p>
            <p><?= $e((string) ($currentProgram['summary'] ?? 'Contenido pendiente de confirmación por Radio Rumbo.')) ?></p>
            <dl>
                <div><dt>Conductor</dt><dd><?= $e((string) ($currentProgram['host'] ?? 'Conductor principal')) ?></dd></div>
                <div><dt>Horario</dt><dd><?= $e((string) ($currentProgram['schedule'] ?? '06:00 - 09:00')) ?></dd></div>
            </dl>
        </article>
    </div>
</section>

<section class="container presentation-note">
    <strong>Listo para presentación</strong>
    <span>La plataforma se encuentra preparada para la carga de información oficial de Radio Rumbo.</span>
</section>

<?php $bannerPosition = 'home_hero'; require dirname(__DIR__) . '/partials/public-banner.php'; ?>

<section class="quick-access container">
    <a href="/en-vivo/">En vivo <span>Audio configurado</span></a>
    <a href="/en-vivo/#video-en-vivo">Video en vivo <span>La radio que se ve</span></a>
    <a href="/en-vivo/#facebook-en-vivo">Facebook <span>Publicaciones oficiales</span></a>
    <a href="/programacion/">Programación <span>Ver horarios</span></a>
</section>

<section class="section-light">
    <div class="container section-heading">
        <span class="section-kicker">Información local</span>
        <h2>Noticias recientes</h2>
        <p>Las noticias oficiales se irán publicando desde el panel administrativo.</p>
    </div>
    <div class="container card-grid">
        <?php if ($news === []): ?>
            <article class="empty-state presentation-empty">Contenido pendiente de confirmación por Radio Rumbo.</article>
        <?php endif; ?>
        <?php foreach (array_slice($news, 0, 3) as $item): ?>
            <article class="news-card">
                <span><?= $e((string) $item['category']) ?></span>
                <h3><?= $e((string) $item['title']) ?></h3>
                <p><?= $e((string) $item['summary']) ?></p>
                <a href="/noticias/">Leer más</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="container split-section">
    <div>
        <span class="section-kicker">Hoy en cabina</span>
        <h2>Programación de hoy</h2>
        <p>Programación referencial pendiente de confirmación final.</p>
        <div class="schedule-list compact">
            <?php foreach (array_slice($schedule, 0, 4) as $program): ?>
                <article>
                    <time><?= $e((string) $program['time']) ?></time>
                    <div>
                        <h3><?= $e((string) $program['name']) ?></h3>
                        <p><?= $e((string) $program['host']) ?> - <?= $e((string) $program['category']) ?></p>
                    </div>
                    <?php if ((bool) $program['live']): ?><span class="live-pill">Ahora</span><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    <aside class="promo-panel">
        <span class="section-kicker">Publicidad local</span>
        <h2>Anuncia con nosotros</h2>
        <p>Espacios radiales y web listos para comercios, eventos y marcas locales.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="/publicidad-contacto/">Ver opciones</a>
            <?php if ($facebookUrl !== ''): ?>
                <a class="btn btn-soft" href="<?= $e($facebookUrl) ?>" target="_blank" rel="noopener noreferrer">Ver Facebook</a>
            <?php endif; ?>
        </div>
    </aside>
</section>

<?php $bannerPosition = 'home_middle'; require dirname(__DIR__) . '/partials/public-banner.php'; ?>

<section class="section-light planned-section">
    <div class="container section-heading">
        <span class="section-kicker">Próximas mejoras</span>
        <h2>Secciones planificadas</h2>
        <p>No incluidas en esta versión de presentación.</p>
    </div>
    <div class="container module-preview">
        <?php foreach (array_slice($plannedModules, 0, 4) as $module): ?>
            <article>
                <strong><?= $e((string) $module['name']) ?></strong>
                <span>Próxima fase</span>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="container section-heading">
    <span class="section-kicker">Voces de la radio</span>
    <h2>Nuestro equipo</h2>
    <p>Sección preparada para cargar el equipo oficial de Radio Rumbo.</p>
</section>
<section class="container team-strip">
    <?php foreach (array_slice($team, 0, 3) as $member): ?>
        <article class="person-card">
            <div class="avatar-placeholder"><?= $e(strtoupper(substr((string) $member['name'], 0, 1))) ?></div>
            <h3><?= $e((string) $member['name']) ?></h3>
            <p><?= $e((string) $member['role']) ?></p>
        </article>
    <?php endforeach; ?>
</section>
