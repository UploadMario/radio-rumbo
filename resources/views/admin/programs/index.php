<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$items = is_array($programs ?? null) ? $programs : [];
$flashSuccess = is_string($flashSuccess ?? null) ? $flashSuccess : null;
$error = is_string($error ?? null) ? $error : null;
$dayLabels = [
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
        <span>Gestion administrativa</span>
        <h1>Programacion</h1>
    </div>
    <div class="admin-profile">
        <a href="/admin/programs/create/">Crear programa</a>
        <a href="/admin/dashboard/">Dashboard</a>
    </div>
</header>

<?php if ($flashSuccess !== null): ?><div class="admin-notice"><?= $e($flashSuccess) ?></div><?php endif; ?>
<?php if ($error !== null): ?><div class="admin-error"><?= $e($error) ?></div><?php endif; ?>

<section class="admin-panel">
    <table>
        <thead><tr><th>Programa</th><th>Dia</th><th>Horario</th><th>Conductor</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php if ($items === []): ?><tr><td colspan="6">No hay programas registrados todavia.</td></tr><?php endif; ?>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $e((string) $item['name']) ?></td>
                    <td><?= $e($dayLabels[(string) $item['day_of_week']] ?? (string) $item['day_of_week']) ?></td>
                    <td><?= $e(substr((string) $item['start_time'], 0, 5)) ?> - <?= $e(substr((string) $item['end_time'], 0, 5)) ?></td>
                    <td><?= $e((string) ($item['host_name'] ?? '')) ?></td>
                    <td><?= ((int) $item['is_active']) === 1 ? 'Activo' : 'Inactivo' ?></td>
                    <td class="table-actions">
                        <a href="/admin/programs/edit/?id=<?= $e((string) $item['id']) ?>">Editar</a>
                        <form action="/admin/programs/" method="post">
                            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
                            <input type="hidden" name="_action" value="toggle">
                            <input type="hidden" name="id" value="<?= $e((string) $item['id']) ?>">
                            <button type="submit"><?= ((int) $item['is_active']) === 1 ? 'Desactivar' : 'Activar' ?></button>
                        </form>
                        <form action="/admin/programs/" method="post">
                            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
                            <input type="hidden" name="_action" value="delete">
                            <input type="hidden" name="id" value="<?= $e((string) $item['id']) ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
