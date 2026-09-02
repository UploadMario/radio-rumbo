<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$contentView = is_string($contentView ?? null) ? $contentView : 'home';
$allowedViews = [
    'advertising-contact',
    'home',
    'live',
    'news',
    'podcasts',
    'schedule',
    'team',
    'upcoming',
];

if (!in_array($contentView, $allowedViews, true)) {
    $contentView = 'upcoming';
}

$pageTitle = is_string($pageTitle ?? null) ? $pageTitle : 'Radio Rumbo';
$active = is_string($active ?? null) ? $active : 'home';
$mock = is_array($mock ?? null) ? $mock : [];
$station = is_array($mock['station'] ?? null) ? $mock['station'] : [];
$currentProgram = is_array($mock['current_program'] ?? null) ? $mock['current_program'] : [];
$e = static fn (string $value): string => SecurityHelper::e($value);
$path = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$canonicalPath = rtrim($path, '/') === '' ? '/' : rtrim($path, '/') . '/';
$appUrl = rtrim((string) ($_ENV['APP_URL'] ?? $_SERVER['APP_URL'] ?? getenv('APP_URL') ?: 'http://localhost:8000'), '/');
$canonicalUrl = $appUrl . $canonicalPath;
$descriptions = [
    'home' => 'Radio Rumbo, la radio que se ve. Audio, video, noticias y programación desde Huancayo para el Valle del Mantaro.',
    'live' => 'Escucha y mira Radio Rumbo en vivo desde Huancayo: señal online, video digital y publicaciones recientes.',
    'news' => 'Publicaciones recientes y noticias de Radio Rumbo desde su página oficial de Facebook.',
    'schedule' => 'Programación radial de Radio Rumbo para Huancayo y la región Junín.',
    'team' => 'Equipo de Radio Rumbo, medio local multiplataforma de Huancayo.',
    'advertising-contact' => 'Anuncia con Radio Rumbo y conecta tu marca con Huancayo y la región Junín.',
];
$description = $descriptions[$contentView] ?? 'Radio Rumbo, emisora local de Huancayo, Perú.';
$logoUrl = $appUrl . '/assets/img/radio-rumbo-logo.png';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($pageTitle) ?></title>
    <meta name="description" content="<?= $e($description) ?>">
    <link rel="canonical" href="<?= $e($canonicalUrl) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $e($pageTitle) ?>">
    <meta property="og:description" content="<?= $e($description) ?>">
    <meta property="og:url" content="<?= $e($canonicalUrl) ?>">
    <meta property="og:image" content="<?= $e($logoUrl) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= $e($description) ?>">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <script type="application/ld+json">
        <?= json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'RadioStation',
            'name' => 'Radio Rumbo',
            'url' => $appUrl,
            'logo' => $logoUrl,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Jr. Ayacucho 297',
                'addressLocality' => 'Huancayo',
                'addressRegion' => 'Junín',
                'addressCountry' => 'PE',
            ],
            'areaServed' => 'Valle del Mantaro',
            'sameAs' => array_values(is_array($station['social_links'] ?? null) ? $station['social_links'] : []),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
    </script>
    <link rel="stylesheet" href="/assets/dist/assets/main.css">
</head>
<body class="site-body">
    <?php require dirname(__DIR__) . '/partials/header.php'; ?>
    <?php require dirname(__DIR__) . '/partials/urgent-notice-bar.php'; ?>
    <main id="contenido" class="site-main">
        <?php require dirname(__DIR__) . '/public/' . $contentView . '.php'; ?>
    </main>
    <?php require dirname(__DIR__) . '/partials/footer.php'; ?>
    <?php require dirname(__DIR__) . '/partials/floating-player.php'; ?>
    <script type="module" src="/assets/dist/assets/main.js"></script>
</body>
</html>
