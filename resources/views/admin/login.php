<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$errors = is_array($errors ?? null) ? $errors : [];
$old = is_array($old ?? null) ? $old : [];
?>
<main class="admin-login">
    <section class="login-card">
        <img class="login-logo" src="/assets/img/radio-rumbo-logo.png" alt="Radio Rumbo" width="92" height="92">
        <span>Backend mínimo Fase 2.0</span>
        <h1>Radio Rumbo Admin</h1>
        <p>Acceso administrativo real para usuarios activos con rol Super Admin o Admin.</p>
        <?php if (isset($errors['_form'])): ?>
            <div class="form-alert"><?= $e((string) $errors['_form']) ?></div>
        <?php endif; ?>
        <form action="/admin/login/" method="post">
            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
            <label>
                Usuario o correo
                <input type="text" name="identifier" value="<?= $e((string) ($old['identifier'] ?? '')) ?>" autocomplete="username">
                <?php if (isset($errors['identifier'])): ?><small><?= $e((string) $errors['identifier']) ?></small><?php endif; ?>
            </label>
            <label>
                Contraseña
                <input type="password" name="password" autocomplete="current-password">
                <?php if (isset($errors['password'])): ?><small><?= $e((string) $errors['password']) ?></small><?php endif; ?>
            </label>
            <button class="btn btn-primary" type="submit">Ingresar</button>
        </form>
        <a href="#">Recuperar acceso: próximamente</a>
    </section>
</main>
