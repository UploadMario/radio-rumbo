<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$errors = is_array($errors ?? null) ? $errors : [];
$old = is_array($old ?? null) ? $old : [];
$action = is_string($action ?? null) ? $action : '/admin/team/create/';
$heading = is_string($heading ?? null) ? $heading : 'Integrante';
?>
<header class="admin-topbar">
    <div><span>Gestion de equipo</span><h1><?= $e($heading) ?></h1></div>
    <div class="admin-profile"><a href="/admin/team/">Volver al listado</a></div>
</header>
<section class="admin-panel">
    <?php if (isset($errors['_form'])): ?><div class="admin-error"><?= $e((string) $errors['_form']) ?></div><?php endif; ?>
    <form class="admin-form" action="<?= $e($action) ?>" method="post">
        <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
        <?php if (isset($old['id'])): ?><input type="hidden" name="id" value="<?= $e((string) $old['id']) ?>"><?php endif; ?>
        <label>Nombre<input type="text" name="name" value="<?= $e((string) ($old['name'] ?? '')) ?>"><?php if (isset($errors['name'])): ?><small><?= $e((string) $errors['name']) ?></small><?php endif; ?></label>
        <label>Rol<input type="text" name="role" value="<?= $e((string) ($old['role'] ?? '')) ?>"><?php if (isset($errors['role'])): ?><small><?= $e((string) $errors['role']) ?></small><?php endif; ?></label>
        <label>Programa asociado<input type="text" name="program_name" value="<?= $e((string) ($old['program_name'] ?? '')) ?>"></label>
        <label>Biografia<textarea name="bio" rows="4"><?= $e((string) ($old['bio'] ?? '')) ?></textarea></label>
        <label>Foto o avatar manual<input type="text" name="photo_path" value="<?= $e((string) ($old['photo_path'] ?? '')) ?>" placeholder="/assets/img/radio-rumbo-logo.png"></label>
        <label>Facebook HTTPS<input type="url" name="facebook_url" value="<?= $e((string) ($old['facebook_url'] ?? '')) ?>"><?php if (isset($errors['facebook_url'])): ?><small><?= $e((string) $errors['facebook_url']) ?></small><?php endif; ?></label>
        <label>Instagram HTTPS<input type="url" name="instagram_url" value="<?= $e((string) ($old['instagram_url'] ?? '')) ?>"><?php if (isset($errors['instagram_url'])): ?><small><?= $e((string) $errors['instagram_url']) ?></small><?php endif; ?></label>
        <label>Orden<input type="number" min="0" name="display_order" value="<?= $e((string) ($old['display_order'] ?? '0')) ?>"></label>
        <label>Estado<select name="is_active"><option value="1" <?= (string) ($old['is_active'] ?? '1') === '1' ? 'selected' : '' ?>>Activo</option><option value="0" <?= (string) ($old['is_active'] ?? '1') === '0' ? 'selected' : '' ?>>Inactivo</option></select></label>
        <button class="btn btn-primary" type="submit">Guardar integrante</button>
    </form>
</section>
