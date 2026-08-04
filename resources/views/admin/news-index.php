<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$items = is_array($newsItems ?? null) ? $newsItems : [];
$flashSuccess = is_string($flashSuccess ?? null) ? $flashSuccess : null;
$error = is_string($error ?? null) ? $error : null;
?>
<header class="admin-topbar">
    <div>
        <span>Gestión administrativa</span>
        <h1>Noticias</h1>
    </div>
    <div class="admin-profile">
        <a href="/admin/news/create/">Crear noticia</a>
        <a href="/admin/dashboard/">Dashboard</a>
    </div>
</header>

<?php if ($flashSuccess !== null): ?><div class="admin-notice"><?= $e($flashSuccess) ?></div><?php endif; ?>
<?php if ($error !== null): ?><div class="admin-error"><?= $e($error) ?></div><?php endif; ?>

<section class="admin-panel">
    <table>
        <thead><tr><th>Título</th><th>Categoría</th><th>Estado</th><th>Publicación</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php if ($items === []): ?>
                <tr><td colspan="5">No hay noticias registradas todavía.</td></tr>
            <?php endif; ?>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $e((string) $item['title']) ?></td>
                    <td><?= $e((string) $item['category']) ?></td>
                    <td><?= $e((string) $item['status']) ?></td>
                    <td><?= $e((string) ($item['published_at'] ?? 'Pendiente')) ?></td>
                    <td class="table-actions">
                        <a href="/admin/news/edit/?id=<?= $e((string) $item['id']) ?>">Editar</a>
                        <form action="/admin/news/" method="post">
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
