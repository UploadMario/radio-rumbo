<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

$view = $_GET['view'] ?? 'dashboard';

if ($view === 'login') {
    rr_render_admin('login', 'Radio Rumbo - Acceso administrativo', 'login');
    return;
}

rr_render_admin('dashboard', 'Radio Rumbo - Panel Administrativo', 'dashboard');
