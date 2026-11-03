<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$errors = is_array($errors ?? null) ? $errors : [];
$old = is_array($old ?? null) ? $old : [];
?>
<main class="admin-login">
    <section class="login-card">
        <img class="login-logo" src="/assets/img/radio-rumbo-logo.png" alt="Radio Rumbo" width="92" height="92">
        <span>Acceso administrativo</span>
        <h1>Radio Rumbo</h1>
        <p>Ingresa para administrar el contenido de la emisora.</p>
        <?php if (isset($errors['_form'])): ?>
            <div class="form-alert"><?= $e((string) $errors['_form']) ?></div>
        <?php endif; ?>
        <form action="/admin/login/" method="post">
            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
            <label for="admin-identifier">
                Usuario o correo
                <input id="admin-identifier" type="text" name="identifier" value="<?= $e((string) ($old['identifier'] ?? '')) ?>" autocomplete="username">
                <?php if (isset($errors['identifier'])): ?><small><?= $e((string) $errors['identifier']) ?></small><?php endif; ?>
            </label>
            <label for="admin-password">
                Contraseña
                <span class="password-field">
                    <input id="admin-password" type="password" name="password" autocomplete="current-password">
                    <button type="button" class="password-toggle" data-password-toggle aria-controls="admin-password" aria-pressed="false">Mostrar</button>
                </span>
                <?php if (isset($errors['password'])): ?><small><?= $e((string) $errors['password']) ?></small><?php endif; ?>
            </label>
            <button class="btn btn-primary" type="submit">Ingresar</button>
        </form>
    </section>
</main>
