<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$message = is_array($message ?? null) ? $message : [];
?>
<header class="admin-topbar">
    <div><span>Detalle de mensaje</span><h1><?= $e((string) ($message['subject'] ?? 'Mensaje')) ?></h1></div>
    <div class="admin-profile"><a href="/admin/messages/">Volver a mensajes</a></div>
</header>
<section class="admin-panel">
    <div class="message-item">
        <strong><?= $e((string) ($message['name'] ?? '')) ?></strong>
        <span><?= $e((string) ($message['email'] ?? 'Correo no indicado')) ?> · <?= $e((string) ($message['phone'] ?? 'Telefono no indicado')) ?></span>
        <small><?= $e((string) ($message['type'] ?? 'general')) ?> · <?= $e((string) ($message['status'] ?? 'new')) ?> · <?= $e((string) ($message['created_at'] ?? '')) ?></small>
    </div>
    <p><?= nl2br($e((string) ($message['message'] ?? ''))) ?></p>
    <p><small>IP: <?= $e((string) ($message['ip_address'] ?? '')) ?> · Navegador: <?= $e((string) ($message['user_agent'] ?? '')) ?></small></p>
    <div class="quick-admin">
        <form action="/admin/messages/" method="post">
            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
            <input type="hidden" name="_action" value="mark-read">
            <input type="hidden" name="id" value="<?= $e((string) ($message['id'] ?? '0')) ?>">
            <button class="btn btn-primary" type="submit">Marcar como leido</button>
        </form>
        <form action="/admin/messages/" method="post">
            <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
            <input type="hidden" name="_action" value="archive">
            <input type="hidden" name="id" value="<?= $e((string) ($message['id'] ?? '0')) ?>">
            <button class="btn btn-soft admin-soft" type="submit">Archivar</button>
        </form>
    </div>
</section>
