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
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($pageTitle) ?></title>
    <meta name="description" content="Radio Rumbo, emisora local de Huancayo. Interfaz visual Fase 1 preparada para conexion futura.">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="site-body">
    <?php require dirname(__DIR__) . '/partials/header.php'; ?>
    <main id="contenido" class="site-main">
        <?php require dirname(__DIR__) . '/public/' . $contentView . '.php'; ?>
    </main>
    <?php require dirname(__DIR__) . '/partials/footer.php'; ?>
    <?php require dirname(__DIR__) . '/partials/floating-player.php'; ?>
    <script src="/assets/js/main.js" defer></script>
    <script src="/assets/js/player.js" defer></script>
</body>
</html>
