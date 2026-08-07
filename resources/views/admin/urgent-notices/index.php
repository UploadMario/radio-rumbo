<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$items = is_array($notices ?? null) ? $notices : [];
$flashSuccess = is_string($flashSuccess ?? null) ? $flashSuccess : null;
$error = is_string($error ?? null) ? $error : null;
?>
<header class="admin-topbar">
    <div><span>Comunicados</span><h1>Avisos urgentes</h1></div>
    <div class="admin-profile"><a href="/admin/urgent-notices/create/">Crear aviso</a><a href="/admin/dashboard/">Dashboard</a></div>
</header>
<?php if ($flashSuccess !== null): ?><div class="admin-notice"><?= $e($flashSuccess) ?></div><?php endif; ?>
<?php if ($error !== null): ?><div class="admin-error"><?= $e($error) ?></div><?php endif; ?>
<section class="admin-panel">
    <table>
        <thead><tr><th>Titulo</th><th>Tipo</th><th>Estado</th><th>Vigencia</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php if ($items === []): ?><tr><td colspan="5">No hay avisos registrados.</td></tr><?php endif; ?>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $e((string) $item['title']) ?></td>
                    <td><?= $e((string) $item['type']) ?></td>
                    <td><?= $e((string) $item['status']) ?></td>
                    <td><?= $e((string) ($item['start_at'] ?? '')) ?> / <?= $e((string) ($item['end_at'] ?? '')) ?></td>
                    <td class="table-actions">
                        <a href="/admin/urgent-notices/edit/?id=<?= $e((string) $item['id']) ?>">Editar</a>
                        <form action="/admin/urgent-notices/" method="post"><input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>"><input type="hidden" name="_action" value="toggle"><input type="hidden" name="id" value="<?= $e((string) $item['id']) ?>"><button type="submit">Activar/desactivar</button></form>
                        <form action="/admin/urgent-notices/" method="post"><input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>"><input type="hidden" name="_action" value="archive"><input type="hidden" name="id" value="<?= $e((string) $item['id']) ?>"><button type="submit">Archivar</button></form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
