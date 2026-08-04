<?php

declare(strict_types=1);

$schedule = is_array($mock['today_schedule'] ?? null) ? $mock['today_schedule'] : [];
$news = is_array($mock['news'] ?? null) ? $mock['news'] : [];
$team = is_array($mock['team'] ?? null) ? $mock['team'] : [];
$services = is_array($mock['advertising_services'] ?? null) ? $mock['advertising_services'] : [];
?>
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="section-kicker">Desde Huancayo para todos</span>
            <h1>Radio Rumbo: moderna, clara y local.</h1>
            <p>Una experiencia visual preparada para escuchar rapido, descubrir contenidos y conectar con la radio en futuras fases.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="/en-vivo/">Escuchar en vivo</a>
                <a class="btn btn-soft" href="/publicidad-contacto/">Escribir a cabina</a>
            </div>
        </div>
        <article class="live-card">
            <span class="live-pill"><?= $e((string) ($currentProgram['status'] ?? 'EN VIVO')) ?></span>
            <h2>En vivo ahora</h2>
            <p class="program-name"><?= $e((string) ($currentProgram['name'] ?? 'Programa Matinal')) ?></p>
            <p><?= $e((string) ($currentProgram['summary'] ?? 'Programa en preparacion.')) ?></p>
            <dl>
                <div><dt>Conductor</dt><dd><?= $e((string) ($currentProgram['host'] ?? 'Conductor Principal')) ?></dd></div>
                <div><dt>Horario</dt><dd><?= $e((string) ($currentProgram['schedule'] ?? '06:00 - 09:00')) ?></dd></div>
            </dl>
        </article>
    </div>
</section>

<section class="quick-access container">
    <a href="/en-vivo/">En vivo <span>Play visual</span></a>
    <a href="/programacion/">Programacion <span>Ver horarios</span></a>
    <a href="/noticias/">Noticias <span>Leer novedades</span></a>
    <a href="/publicidad-contacto/">Publicidad local <span>Anuncia con nosotros</span></a>
</section>

<section class="section-light">
    <div class="container section-heading">
        <span class="section-kicker">Informacion local</span>
        <h2>Ultimas noticias</h2>
    </div>
    <div class="container card-grid">
        <?php foreach (array_slice($news, 0, 3) as $item): ?>
            <article class="news-card">
                <span><?= $e((string) $item['category']) ?></span>
                <h3><?= $e((string) $item['title']) ?></h3>
                <p><?= $e((string) $item['summary']) ?></p>
                <a href="/noticias/">Leer mas</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="container split-section">
    <div>
        <span class="section-kicker">Hoy en cabina</span>
        <h2>Programacion de hoy</h2>
        <div class="schedule-list compact">
            <?php foreach (array_slice($schedule, 0, 4) as $program): ?>
                <article>
                    <time><?= $e((string) $program['time']) ?></time>
                    <div>
                        <h3><?= $e((string) $program['name']) ?></h3>
                        <p><?= $e((string) $program['host']) ?> · <?= $e((string) $program['category']) ?></p>
                    </div>
                    <?php if ((bool) $program['live']): ?><span class="live-pill">Ahora</span><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    <aside class="promo-panel">
        <span class="section-kicker">Publicidad local</span>
        <h2>Anuncia con nosotros</h2>
        <p>Espacios radiales y web preparados para comercios, eventos y marcas locales.</p>
        <a class="btn btn-primary" href="/publicidad-contacto/">Ver opciones</a>
    </aside>
</section>

<section class="section-light">
    <div class="container section-heading">
        <span class="section-kicker">Voces de la radio</span>
        <h2>Nuestro equipo</h2>
    </div>
    <div class="container team-strip">
        <?php foreach (array_slice($team, 0, 3) as $member): ?>
            <article class="person-card">
                <div class="avatar-placeholder"><?= $e(substr((string) $member['name'], 0, 1)) ?></div>
                <h3><?= $e((string) $member['name']) ?></h3>
                <p><?= $e((string) $member['role']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
