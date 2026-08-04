<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$items = is_array($messages ?? null) ? $messages : [];
$flashSuccess = is_string($flashSuccess ?? null) ? $flashSuccess : null;
$error = is_string($error ?? null) ? $error : null;
?>
<header class="admin-topbar">
    <div><span>Bandeja administrativa</span><h1>Mensajes</h1></div>
    <div class="admin-profile"><a href="/admin/messages/">Todos</a><a href="/admin/messages/?status=new">Nuevos</a></div>
</header>
<?php if ($flashSuccess !== null): ?><div class="admin-notice"><?= $e($flashSuccess) ?></div><?php endif; ?>
<?php if ($error !== null): ?><div class="admin-error"><?= $e($error) ?></div><?php endif; ?>
<section class="admin-panel">
    <table>
        <thead><tr><th>Remitente</th><th>Tipo</th><th>Asunto</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php if ($items === []): ?><tr><td colspan="6">No hay mensajes para mostrar.</td></tr><?php endif; ?>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $e((string) $item['name']) ?></td>
                    <td><?= $e((string) $item['type']) ?></td>
                    <td><?= $e((string) $item['subject']) ?></td>
                    <td><?= $e((string) $item['status']) ?></td>
                    <td><?= $e((string) $item['created_at']) ?></td>
                    <td class="table-actions">
                        <a href="/admin/messages/view/?id=<?= $e((string) $item['id']) ?>">Ver</a>
                        <form action="/admin/messages/" method="post">
                            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
                            <input type="hidden" name="_action" value="mark-read">
                            <input type="hidden" name="id" value="<?= $e((string) $item['id']) ?>">
                            <button type="submit">Leido</button>
                        </form>
                        <form action="/admin/messages/" method="post">
                            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
                            <input type="hidden" name="_action" value="archive">
                            <input type="hidden" name="id" value="<?= $e((string) $item['id']) ?>">
                            <button type="submit">Archivar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
