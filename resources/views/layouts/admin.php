<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$contentView = is_string($contentView ?? null) ? $contentView : 'dashboard';
$allowedViews = ['dashboard', 'login'];

if (!in_array($contentView, $allowedViews, true)) {
    $contentView = 'dashboard';
}

$pageTitle = is_string($pageTitle ?? null) ? $pageTitle : 'Panel Administrativo Radio Rumbo';
$active = is_string($active ?? null) ? $active : 'dashboard';
$mock = is_array($mock ?? null) ? $mock : [];
$e = static fn (string $value): string => SecurityHelper::e($value);
$isLogin = $contentView === 'login';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($pageTitle) ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="<?= $isLogin ? 'admin-login-body' : 'admin-shell-body' ?>">
    <?php if ($isLogin): ?>
        <?php require dirname(__DIR__) . '/admin/login.php'; ?>
    <?php else: ?>
        <div class="admin-shell">
            <?php require dirname(__DIR__) . '/partials/admin-sidebar.php'; ?>
            <main class="admin-main" id="contenido">
                <?php require dirname(__DIR__) . '/admin/dashboard.php'; ?>
            </main>
        </div>
    <?php endif; ?>
    <script src="/assets/js/main.js" defer></script>
</body>
</html>
