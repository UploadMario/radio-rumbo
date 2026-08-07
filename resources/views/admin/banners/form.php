<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;
use App\Validators\BannerValidator;

$errors = is_array($errors ?? null) ? $errors : [];
$old = is_array($old ?? null) ? $old : [];
$action = is_string($action ?? null) ? $action : '/admin/banners/create/';
$heading = is_string($heading ?? null) ? $heading : 'Banner';
?>
<header class="admin-topbar"><div><span>Banners</span><h1><?= $e($heading) ?></h1></div><div class="admin-profile"><a href="/admin/banners/">Volver</a></div></header>
<section class="admin-panel">
    <?php if (isset($errors['_form'])): ?><div class="admin-error"><?= $e((string) $errors['_form']) ?></div><?php endif; ?>
    <form class="admin-form" action="<?= $e($action) ?>" method="post">
        <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
        <label>Titulo<input name="title" value="<?= $e((string) ($old['title'] ?? '')) ?>"><?php if (isset($errors['title'])): ?><small><?= $e((string) $errors['title']) ?></small><?php endif; ?></label>
        <label>Subtitulo<textarea name="subtitle" rows="3"><?= $e((string) ($old['subtitle'] ?? '')) ?></textarea></label>
        <label>Imagen manual o URL HTTPS<input name="image_path" value="<?= $e((string) ($old['image_path'] ?? '')) ?>"><?php if (isset($errors['image_path'])): ?><small><?= $e((string) $errors['image_path']) ?></small><?php endif; ?></label>
        <label>Enlace HTTPS<input name="link_url" value="<?= $e((string) ($old['link_url'] ?? '')) ?>"><?php if (isset($errors['link_url'])): ?><small><?= $e((string) $errors['link_url']) ?></small><?php endif; ?></label>
        <label>Posicion<select name="position"><?php foreach (BannerValidator::POSITIONS as $value): ?><option value="<?= $e($value) ?>" <?= ($old['position'] ?? '') === $value ? 'selected' : '' ?>><?= $e($value) ?></option><?php endforeach; ?></select></label>
        <label>Estado<select name="status"><?php foreach (BannerValidator::STATUSES as $value): ?><option value="<?= $e($value) ?>" <?= ($old['status'] ?? '') === $value ? 'selected' : '' ?>><?= $e($value) ?></option><?php endforeach; ?></select></label>
        <label>Inicio<input type="datetime-local" name="start_at" value="<?= $e(str_replace(' ', 'T', (string) ($old['start_at'] ?? ''))) ?>"></label>
        <label>Fin<input type="datetime-local" name="end_at" value="<?= $e(str_replace(' ', 'T', (string) ($old['end_at'] ?? ''))) ?>"><?php if (isset($errors['end_at'])): ?><small><?= $e((string) $errors['end_at']) ?></small><?php endif; ?></label>
        <label>Orden<input type="number" min="0" name="display_order" value="<?= $e((string) ($old['display_order'] ?? '0')) ?>"></label>
        <button class="btn btn-primary" type="submit">Guardar banner</button>
    </form>
</section>
