<?php

declare(strict_types=1);

$schedule = is_array($mock['today_schedule'] ?? null) ? $mock['today_schedule'] : [];
$team = is_array($mock['team'] ?? null) ? $mock['team'] : [];
$facebookUrl = is_string($station['facebook_page_url'] ?? null) ? $station['facebook_page_url'] : '';
$nextProgram = is_array($mock['next_program'] ?? null) ? $mock['next_program'] : null;
$about = is_string($station['about'] ?? null) ? $station['about'] : '';
$history = is_string($station['history'] ?? null) ? $station['history'] : '';
$frequencyFm = is_string($station['frequency_fm'] ?? null) ? $station['frequency_fm'] : '91.1 FM';
$frequencyAm = is_string($station['frequency_am'] ?? null) ? $station['frequency_am'] : '1560 AM';
$coverage = is_string($station['coverage'] ?? null) ? $station['coverage'] : 'Valle del Mantaro';
?>
<section class="hero-section home-hero">
    <img class="hero-watermark" src="/assets/img/radio-rumbo-isotype-web.png" alt="" aria-hidden="true">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="section-kicker">Huancayo, Perú</span>
            <h1>La radio que se ve</h1>
            <p>Audio, video, noticias y programación desde Huancayo.</p>
            <div class="frequency-lockup" aria-label="Frecuencias de Radio Rumbo">
                <span><strong>91.1</strong><small>FM</small></span>
                <span><strong>1560</strong><small>AM</small></span>
            </div>
            <div class="hero-actions">
                <button class="btn btn-primary" type="button" data-player-toggle>Escuchar en vivo</button>
                <a class="btn btn-soft" href="/en-vivo/">Abrir En vivo</a>
            </div>
            <article class="program-summary hero-program-card">
                <span>Programa actual</span>
                <strong><?= $e((string) ($currentProgram['name'] ?? 'Rumbo Mañanero')) ?></strong>
                <small><?= $e((string) ($currentProgram['schedule'] ?? '06:00 - 09:00')) ?> - <?= $e((string) ($currentProgram['host'] ?? 'Cabina Radio Rumbo')) ?></small>
                <?php if ($nextProgram !== null): ?>
                    <small class="player-next">Luego: <?= $e((string) ($nextProgram['name'] ?? 'Próximo bloque disponible')) ?></small>
                <?php endif; ?>
            </article>
        </div>
        <?php
        $videoTitle = 'Video en directo';
        $videoDescription = 'Mira la cabina y programas especiales sin salir de la web.';
        $videoLoading = 'lazy';
        $videoCardClass = 'live-video-card--home';
        require dirname(__DIR__) . '/partials/live-video-player.php';
        ?>
    </div>
</section>

<section class="container broadcast-now" aria-label="Ahora en Radio Rumbo">
    <article>
        <span>Ahora al aire</span>
        <strong><?= $e((string) ($currentProgram['name'] ?? 'Programación en pausa')) ?></strong>
        <small><?= $e((string) ($currentProgram['schedule'] ?? 'Horario por confirmar')) ?></small>
    </article>
    <article>
        <span>Siguiente</span>
        <strong><?= $e((string) ($nextProgram['name'] ?? 'Próximo bloque disponible')) ?></strong>
        <small><?= $e((string) ($nextProgram['schedule'] ?? 'Horario por confirmar')) ?></small>
    </article>
    <article>
        <span>Noticias</span>
        <strong>Publicaciones recientes</strong>
        <small>Actualidad desde Facebook</small>
    </article>
    <article>
        <span>Transmisión</span>
        <strong><?= $e($frequencyFm) ?> / <?= $e($frequencyAm) ?></strong>
        <small>Audio, video y señal online</small>
    </article>
</section>

<?php $bannerPosition = 'home_hero'; require dirname(__DIR__) . '/partials/public-banner.php'; ?>

<section class="container home-schedule-section">
    <div class="section-heading compact-heading broadcast-heading">
        <div>
            <span class="section-kicker">Hoy en cabina</span>
            <h2>Programación de hoy</h2>
            <p>Ahora, siguiente y los próximos bloques de la señal.</p>
        </div>
        <a class="btn btn-soft" href="/programacion/">Ver programación completa</a>
    </div>
    <div class="schedule-list compact home-schedule-list home-schedule-broadcast">
        <?php foreach (array_slice($schedule, 0, 4) as $program): ?>
            <article class="<?= (bool) ($program['live'] ?? false) ? 'is-live' : '' ?>">
                <time><?= $e((string) ($program['time'] ?? '')) ?></time>
                <div>
                    <h3><?= $e((string) ($program['name'] ?? 'Bloque Radio Rumbo')) ?></h3>
                    <p><?= $e((string) ($program['host'] ?? 'Cabina Radio Rumbo')) ?> - <?= $e((string) ($program['category'] ?? 'Radio Rumbo')) ?></p>
                </div>
                <?php if ((bool) ($program['live'] ?? false)): ?><span class="live-pill">Ahora</span><?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section-light home-news-section">
    <div class="container section-heading editorial-heading">
        <div>
            <span class="section-kicker">Información local</span>
            <h2>Noticias recientes</h2>
        </div>
        <p>Las publicaciones recientes se muestran desde Facebook para mantener la información actualizada.</p>
    </div>
    <div class="container editorial-news-grid editorial-news-grid--single">
        <article class="news-card featured-editorial">
            <span>Facebook oficial</span>
            <h3>Últimas publicaciones de Radio Rumbo</h3>
            <p>Noticias locales, regionales y nacionales compartidas desde la presencia digital de la radio.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="/noticias/">Ver noticias</a>
                <?php if ($facebookUrl !== ''): ?>
                    <a class="btn btn-soft admin-soft" href="<?= $e($facebookUrl) ?>" target="_blank" rel="noopener noreferrer">Ver Facebook oficial</a>
                <?php endif; ?>
            </div>
        </article>
    </div>
</section>

<section class="container identity-section">
    <div class="identity-copy">
        <span class="section-kicker">Medio multiplataforma</span>
        <h2>Desde 1996 junto al Valle del Mantaro</h2>
        <p><?= $e($about) ?></p>
        <p><?= $e($history) ?></p>
    </div>
    <div class="identity-facts" aria-label="Frecuencias y cobertura">
        <article><span>FM</span><strong><?= $e($frequencyFm) ?></strong></article>
        <article><span>AM</span><strong><?= $e($frequencyAm) ?></strong></article>
        <article><span>Cobertura</span><strong><?= $e($coverage) ?></strong></article>
    </div>
</section>

<?php $bannerPosition = 'home_middle'; require dirname(__DIR__) . '/partials/public-banner.php'; ?>

<?php if ($team !== []): ?>
<section class="container section-heading team-home-heading">
    <span class="section-kicker">Voces de la radio</span>
    <h2>Nuestro equipo</h2>
    <p>Las voces y áreas que acompañan la señal de Radio Rumbo.</p>
</section>
<section class="container team-strip">
    <?php foreach (array_slice($team, 0, 4) as $member): ?>
        <article class="person-card">
            <div class="avatar-placeholder"><?= $e(strtoupper(substr((string) $member['name'], 0, 1))) ?></div>
            <h3><?= $e((string) $member['name']) ?></h3>
            <p><?= $e((string) $member['role']) ?></p>
        </article>
    <?php endforeach; ?>
</section>
<?php endif; ?>

<section class="container final-cta">
    <div>
        <span class="section-kicker">Publicidad y contacto</span>
        <h2>Anuncia con nosotros y llega a más oyentes en Huancayo.</h2>
        <p>Espacios radiales y web para comercios, campañas y eventos locales.</p>
    </div>
    <a class="btn btn-primary" href="/publicidad-contacto/">Anuncia con nosotros</a>
</section>
