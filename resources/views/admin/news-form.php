<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$errors = is_array($errors ?? null) ? $errors : [];
$old = is_array($old ?? null) ? $old : [];
$action = is_string($action ?? null) ? $action : '/admin/news/create/';
$heading = is_string($heading ?? null) ? $heading : 'Noticia';
?>
<header class="admin-topbar">
    <div>
        <span>Gestión de noticias</span>
        <h1><?= $e($heading) ?></h1>
    </div>
    <div class="admin-profile">
        <a href="/admin/news/">Volver al listado</a>
    </div>
</header>

<section class="admin-panel">
    <?php if (isset($errors['_form'])): ?><div class="admin-error"><?= $e((string) $errors['_form']) ?></div><?php endif; ?>
    <form class="admin-form" action="<?= $e($action) ?>" method="post">
        <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
        <?php if (isset($old['id'])): ?><input type="hidden" name="id" value="<?= $e((string) $old['id']) ?>"><?php endif; ?>

        <label>Título
            <input type="text" name="title" value="<?= $e((string) ($old['title'] ?? '')) ?>">
            <?php if (isset($errors['title'])): ?><small><?= $e((string) $errors['title']) ?></small><?php endif; ?>
        </label>

        <label>Slug
            <input type="text" name="slug" value="<?= $e((string) ($old['slug'] ?? '')) ?>" placeholder="Se genera desde el título si queda vacío">
        </label>

        <label>Categoría
            <input type="text" name="category" value="<?= $e((string) ($old['category'] ?? '')) ?>">
            <?php if (isset($errors['category'])): ?><small><?= $e((string) $errors['category']) ?></small><?php endif; ?>
        </label>

        <label>Resumen
            <textarea name="summary" rows="3"><?= $e((string) ($old['summary'] ?? '')) ?></textarea>
            <?php if (isset($errors['summary'])): ?><small><?= $e((string) $errors['summary']) ?></small><?php endif; ?>
        </label>

        <label>Contenido
            <textarea name="body" rows="8"><?= $e((string) ($old['body'] ?? '')) ?></textarea>
            <?php if (isset($errors['body'])): ?><small><?= $e((string) $errors['body']) ?></small><?php endif; ?>
        </label>

        <label>Estado
            <select name="status">
                <?php foreach (['draft' => 'Borrador', 'published' => 'Publicada', 'archived' => 'Archivada'] as $value => $label): ?>
                    <option value="<?= $e($value) ?>" <?= ($old['status'] ?? 'draft') === $value ? 'selected' : '' ?>>
                        <?= $e($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['status'])): ?><small><?= $e((string) $errors['status']) ?></small><?php endif; ?>
        </label>

        <label>Fecha de publicación
            <input type="datetime-local" name="published_at" value="<?= $e(str_replace(' ', 'T', (string) ($old['published_at'] ?? ''))) ?>">
        </label>

        <button class="btn btn-primary" type="submit">Guardar noticia</button>
    </form>
</section>
