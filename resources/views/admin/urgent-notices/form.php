<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;
use App\Validators\UrgentNoticeValidator;

$errors = is_array($errors ?? null) ? $errors : [];
$old = is_array($old ?? null) ? $old : [];
$action = is_string($action ?? null) ? $action : '/admin/urgent-notices/create/';
$heading = is_string($heading ?? null) ? $heading : 'Aviso';
?>
<header class="admin-topbar"><div><span>Avisos urgentes</span><h1><?= $e($heading) ?></h1></div><div class="admin-profile"><a href="/admin/urgent-notices/">Volver</a></div></header>
<section class="admin-panel">
    <?php if (isset($errors['_form'])): ?><div class="admin-error"><?= $e((string) $errors['_form']) ?></div><?php endif; ?>
    <form class="admin-form" action="<?= $e($action) ?>" method="post">
        <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
        <label>Titulo<input name="title" value="<?= $e((string) ($old['title'] ?? '')) ?>"><?php if (isset($errors['title'])): ?><small><?= $e((string) $errors['title']) ?></small><?php endif; ?></label>
        <label>Mensaje<textarea name="message" rows="4"><?= $e((string) ($old['message'] ?? '')) ?></textarea><?php if (isset($errors['message'])): ?><small><?= $e((string) $errors['message']) ?></small><?php endif; ?></label>
        <label>Tipo<select name="type"><?php foreach (UrgentNoticeValidator::TYPES as $value): ?><option value="<?= $e($value) ?>" <?= ($old['type'] ?? '') === $value ? 'selected' : '' ?>><?= $e($value) ?></option><?php endforeach; ?></select></label>
        <label>Estado<select name="status"><?php foreach (UrgentNoticeValidator::STATUSES as $value): ?><option value="<?= $e($value) ?>" <?= ($old['status'] ?? '') === $value ? 'selected' : '' ?>><?= $e($value) ?></option><?php endforeach; ?></select></label>
        <label>Inicio<input type="datetime-local" name="start_at" value="<?= $e(str_replace(' ', 'T', (string) ($old['start_at'] ?? ''))) ?>"></label>
        <label>Fin<input type="datetime-local" name="end_at" value="<?= $e(str_replace(' ', 'T', (string) ($old['end_at'] ?? ''))) ?>"><?php if (isset($errors['end_at'])): ?><small><?= $e((string) $errors['end_at']) ?></small><?php endif; ?></label>
        <label>Orden<input type="number" min="0" name="display_order" value="<?= $e((string) ($old['display_order'] ?? '0')) ?>"></label>
        <button class="btn btn-primary" type="submit">Guardar aviso</button>
    </form>
</section>
