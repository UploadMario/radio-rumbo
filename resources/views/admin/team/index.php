<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$items = is_array($teamMembers ?? null) ? $teamMembers : [];
$flashSuccess = is_string($flashSuccess ?? null) ? $flashSuccess : null;
$error = is_string($error ?? null) ? $error : null;
?>
<header class="admin-topbar">
    <div><span>Gestion administrativa</span><h1>Equipo</h1></div>
    <div class="admin-profile"><a href="/admin/team/create/">Crear integrante</a><a href="/admin/dashboard/">Dashboard</a></div>
</header>
<?php if ($flashSuccess !== null): ?><div class="admin-notice"><?= $e($flashSuccess) ?></div><?php endif; ?>
<?php if ($error !== null): ?><div class="admin-error"><?= $e($error) ?></div><?php endif; ?>
<section class="admin-panel">
    <table>
        <thead><tr><th>Nombre</th><th>Rol</th><th>Programa</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php if ($items === []): ?><tr><td colspan="5">No hay integrantes registrados todavia.</td></tr><?php endif; ?>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $e((string) $item['name']) ?></td>
                    <td><?= $e((string) $item['role']) ?></td>
                    <td><?= $e((string) $item['program_name']) ?></td>
                    <td><?= ((int) $item['is_active']) === 1 ? 'Activo' : 'Inactivo' ?></td>
                    <td class="table-actions">
                        <a href="/admin/team/edit/?id=<?= $e((string) $item['id']) ?>">Editar</a>
                        <form action="/admin/team/" method="post">
                            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
                            <input type="hidden" name="_action" value="toggle">
                            <input type="hidden" name="id" value="<?= $e((string) $item['id']) ?>">
                            <button type="submit"><?= ((int) $item['is_active']) === 1 ? 'Desactivar' : 'Activar' ?></button>
                        </form>
                        <form action="/admin/team/" method="post">
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
