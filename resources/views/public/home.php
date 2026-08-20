<?php

declare(strict_types=1);

$schedule = is_array($mock['today_schedule'] ?? null) ? $mock['today_schedule'] : [];
$news = is_array($mock['news'] ?? null) ? $mock['news'] : [];
$team = is_array($mock['team'] ?? null) ? $mock['team'] : [];
$facebookUrl = is_string($station['facebook_page_url'] ?? null) ? $station['facebook_page_url'] : '';
$nextProgram = is_array($mock['next_program'] ?? null) ? $mock['next_program'] : null;
$featuredNews = $news[0] ?? null;
$secondaryNews = array_slice($news, 1, 2);
$about = is_string($station['about'] ?? null) ? $station['about'] : '';
$history = is_string($station['history'] ?? null) ? $station['history'] : '';
$frequencyFm = is_string($station['frequency_fm'] ?? null) ? $station['frequency_fm'] : '91.1 FM';
$frequencyAm = is_string($station['frequency_am'] ?? null) ? $station['frequency_am'] : '1560 AM';
$coverage = is_string($station['coverage'] ?? null) ? $station['coverage'] : 'Valle del Mantaro';
?>
<section class="hero-section home-hero">
    <img class="hero-watermark" src="/assets/img/radio-rumbo-logo.png" alt="" aria-hidden="true">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="section-kicker">Huancayo, Perú</span>
            <h1>La radio que se ve</h1>
            <p>Audio, video, noticias y programación desde Huancayo.</p>
            <div class="hero-signal-row" aria-label="Secciones principales">
                <span><?= $e($frequencyFm) ?></span>
                <span><?= $e($frequencyAm) ?></span>
                <span>Señal online</span>
                <span>Video en vivo</span>
            </div>
            <div class="hero-actions">
                <button class="btn btn-primary" type="button" data-player-toggle>Escuchar en vivo</button>
                <a class="btn btn-soft" href="/en-vivo/#video-en-vivo">Ver transmisión</a>
                <a class="btn btn-soft" href="/publicidad-contacto/">Anuncia con nosotros</a>
            </div>
        </div>
        <article class="live-card hero-live-card">
            <span class="live-pill"><?= $e((string) ($currentProgram['status'] ?? 'EN VIVO')) ?></span>
            <h2>En vivo ahora</h2>
            <p class="program-name"><?= $e((string) ($currentProgram['name'] ?? 'Rumbo Mañanero')) ?></p>
            <p><?= $e((string) ($currentProgram['summary'] ?? 'Acompañamiento, información y música para Huancayo.')) ?></p>
            <dl>
                <div><dt>Cabina</dt><dd><?= $e((string) ($currentProgram['host'] ?? 'Cabina Radio Rumbo')) ?></dd></div>
                <div><dt>Horario</dt><dd><?= $e((string) ($currentProgram['schedule'] ?? '06:00 - 09:00')) ?></dd></div>
            </dl>
            <?php if ($nextProgram !== null): ?>
                <div class="next-mini">
                    <span>Siguiente bloque</span>
                    <strong><?= $e((string) ($nextProgram['name'] ?? 'Próximo bloque disponible')) ?></strong>
                    <small><?= $e((string) ($nextProgram['schedule'] ?? 'Horario por confirmar')) ?></small>
                </div>
            <?php endif; ?>
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

<section class="container today-strip" aria-label="Hoy en Radio Rumbo">
    <article>
        <span>Al aire</span>
        <strong><?= $e((string) ($currentProgram['name'] ?? 'Programación en pausa')) ?></strong>
        <small><?= $e((string) ($currentProgram['host'] ?? 'Radio Rumbo')) ?></small>
    </article>
    <article>
        <span>Próximo</span>
        <strong><?= $e((string) ($nextProgram['name'] ?? 'Próximo bloque disponible')) ?></strong>
        <small><?= $e((string) ($nextProgram['schedule'] ?? 'Horario por confirmar')) ?></small>
    </article>
    <article>
        <span>Última noticia</span>
        <strong><?= $e((string) ($featuredNews['title'] ?? 'Noticias de Huancayo')) ?></strong>
        <small><?= $e((string) ($featuredNews['category'] ?? 'Información local')) ?></small>
    </article>
    <article>
        <span>Transmisión</span>
        <strong><?= $e($frequencyFm) ?> / <?= $e($frequencyAm) ?></strong>
        <small>Audio, video y señal online</small>
    </article>
</section>

<?php $bannerPosition = 'home_hero'; require dirname(__DIR__) . '/partials/public-banner.php'; ?>

<section class="section-light home-news-section">
    <div class="container section-heading editorial-heading">
        <div>
            <span class="section-kicker">Información local</span>
            <h2>Noticias recientes</h2>
        </div>
        <p>Las publicaciones recientes se muestran desde Facebook para mantener la información actualizada.</p>
    </div>
    <div class="container editorial-news-grid">
        <?php if ($news === []): ?>
            <article class="empty-state presentation-empty">Muy pronto tendremos nuevas publicaciones para nuestros oyentes.</article>
        <?php endif; ?>
        <?php if (is_array($featuredNews)): ?>
            <article class="news-card featured-editorial">
                <span><?= $e((string) ($featuredNews['category'] ?? 'Huancayo')) ?></span>
                <h3><?= $e((string) ($featuredNews['title'] ?? 'Últimas noticias de Huancayo')) ?></h3>
                <p><?= $e((string) ($featuredNews['summary'] ?? '')) ?></p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="/noticias/">Ver noticias</a>
                    <?php if ($facebookUrl !== ''): ?>
                        <a class="btn btn-soft admin-soft" href="<?= $e($facebookUrl) ?>" target="_blank" rel="noopener noreferrer">Ver Facebook oficial</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endif; ?>
        <div class="editorial-side-list">
            <?php foreach ($secondaryNews as $item): ?>
                <article class="news-card compact-news">
                    <span><?= $e((string) ($item['category'] ?? 'Radio Rumbo')) ?></span>
                    <h3><?= $e((string) ($item['title'] ?? 'Información local')) ?></h3>
                    <p><?= $e((string) ($item['summary'] ?? '')) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="container home-schedule-section">
    <div class="section-heading compact-heading">
        <span class="section-kicker">Hoy en cabina</span>
        <h2>Programación de hoy</h2>
        <p>Bloques, voces y horarios para acompañar tu día.</p>
    </div>
    <div class="schedule-list compact home-schedule-list">
        <?php foreach (array_slice($schedule, 0, 5) as $program): ?>
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

<section class="home-video-cta">
    <div class="container split-section">
        <div>
            <span class="section-kicker">La radio que se ve</span>
            <h2>Radio tradicional, redes sociales y video digital en vivo.</h2>
            <p>Escucha la señal online, mira la transmisión y sigue las noticias locales, regionales y nacionales desde Huancayo.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="/en-vivo/#video-en-vivo">Ver transmisión</a>
                <?php if ($facebookUrl !== ''): ?>
                    <a class="btn btn-soft" href="<?= $e($facebookUrl) ?>" target="_blank" rel="noopener noreferrer">Ver Facebook</a>
                <?php endif; ?>
            </div>
        </div>
        <aside class="signal-card">
            <strong>Radio Rumbo en vivo</strong>
            <span><?= $e((string) ($currentProgram['name'] ?? 'Programación en pausa')) ?></span>
        </aside>
    </div>
</section>

<?php $bannerPosition = 'home_middle'; require dirname(__DIR__) . '/partials/public-banner.php'; ?>

<section class="container section-heading team-home-heading">
    <span class="section-kicker">Voces de la radio</span>
    <h2>Nuestro equipo</h2>
    <p>Las voces y áreas que acompañan la señal de Radio Rumbo.</p>
</section>
<section class="container team-strip">
    <?php if ($team === []): ?>
        <article class="person-card">
            <div class="avatar-placeholder">R</div>
            <h3>Equipo Radio Rumbo</h3>
            <p>Las voces y áreas de la radio se publicarán desde el panel cuando la información oficial esté lista.</p>
        </article>
    <?php endif; ?>
    <?php foreach (array_slice($team, 0, 4) as $member): ?>
        <article class="person-card">
            <div class="avatar-placeholder"><?= $e(strtoupper(substr((string) $member['name'], 0, 1))) ?></div>
            <h3><?= $e((string) $member['name']) ?></h3>
            <p><?= $e((string) $member['role']) ?></p>
        </article>
    <?php endforeach; ?>
</section>

<section class="container final-cta">
    <div>
        <span class="section-kicker">Publicidad y contacto</span>
        <h2>Anuncia con nosotros y llega a más oyentes en Huancayo.</h2>
        <p>Espacios radiales y web para comercios, campañas y eventos locales.</p>
    </div>
    <a class="btn btn-primary" href="/publicidad-contacto/">Anuncia con nosotros</a>
</section>
