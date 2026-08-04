<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$errors = is_array($errors ?? null) ? $errors : [];
$old = is_array($old ?? null) ? $old : [];
$action = is_string($action ?? null) ? $action : '/admin/programs/create/';
$heading = is_string($heading ?? null) ? $heading : 'Programa';
$days = [
    'monday' => 'Lunes',
    'tuesday' => 'Martes',
    'wednesday' => 'Miercoles',
    'thursday' => 'Jueves',
    'friday' => 'Viernes',
    'saturday' => 'Sabado',
    'sunday' => 'Domingo',
];
?>
<header class="admin-topbar">
    <div>
        <span>Gestion de programacion</span>
        <h1><?= $e($heading) ?></h1>
    </div>
    <div class="admin-profile"><a href="/admin/programs/">Volver al listado</a></div>
</header>

<section class="admin-panel">
    <?php if (isset($errors['_form'])): ?><div class="admin-error"><?= $e((string) $errors['_form']) ?></div><?php endif; ?>
    <form class="admin-form" action="<?= $e($action) ?>" method="post">
        <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
        <?php if (isset($old['id'])): ?><input type="hidden" name="id" value="<?= $e((string) $old['id']) ?>"><?php endif; ?>

        <label>Nombre
            <input type="text" name="name" value="<?= $e((string) ($old['name'] ?? '')) ?>">
            <?php if (isset($errors['name'])): ?><small><?= $e((string) $errors['name']) ?></small><?php endif; ?>
        </label>
        <label>Slug
            <input type="text" name="slug" value="<?= $e((string) ($old['slug'] ?? '')) ?>" placeholder="Se genera automaticamente si queda vacio">
        </label>
        <label>Descripcion
            <textarea name="description" rows="4"><?= $e((string) ($old['description'] ?? '')) ?></textarea>
        </label>
        <label>Conductor
            <input type="text" name="host_name" value="<?= $e((string) ($old['host_name'] ?? '')) ?>" placeholder="Conductor principal">
        </label>
        <label>Dia
            <select name="day_of_week">
                <?php foreach ($days as $value => $label): ?>
                    <option value="<?= $e($value) ?>" <?= ($old['day_of_week'] ?? 'monday') === $value ? 'selected' : '' ?>><?= $e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['day_of_week'])): ?><small><?= $e((string) $errors['day_of_week']) ?></small><?php endif; ?>
        </label>
        <label>Hora inicio
            <input type="time" name="start_time" value="<?= $e(substr((string) ($old['start_time'] ?? '06:00'), 0, 5)) ?>">
            <?php if (isset($errors['start_time'])): ?><small><?= $e((string) $errors['start_time']) ?></small><?php endif; ?>
        </label>
        <label>Hora fin
            <input type="time" name="end_time" value="<?= $e(substr((string) ($old['end_time'] ?? '09:00'), 0, 5)) ?>">
            <?php if (isset($errors['end_time'])): ?><small><?= $e((string) $errors['end_time']) ?></small><?php endif; ?>
        </label>
        <label>Categoria
            <input type="text" name="category" value="<?= $e((string) ($old['category'] ?? '')) ?>">
        </label>
        <label>Orden
            <input type="number" min="0" name="display_order" value="<?= $e((string) ($old['display_order'] ?? '0')) ?>">
        </label>
        <label>Estado
            <select name="is_active">
                <option value="1" <?= (string) ($old['is_active'] ?? '1') === '1' ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= (string) ($old['is_active'] ?? '1') === '0' ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </label>
        <button class="btn btn-primary" type="submit">Guardar programa</button>
    </form>
</section>
