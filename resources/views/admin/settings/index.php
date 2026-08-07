<?php

declare(strict_types=1);

use App\Helpers\SecurityHelper;

$values = is_array($settingsValues ?? null) ? $settingsValues : [];
$errors = is_array($errors ?? null) ? $errors : [];
$canEdit = (bool) ($canEditSettings ?? false);
$flashSuccess = is_string($flashSuccess ?? null) ? $flashSuccess : null;
$error = is_string($error ?? null) ? $error : null;
$fields = [
    'Identidad de la radio' => [
        'RADIO_NAME' => 'Nombre de la radio',
        'RADIO_TAGLINE' => 'Lema',
        'RADIO_LOCATION' => 'Ubicacion',
    ],
    'Transmision en vivo' => [
        'stream_audio_url' => 'URL de audio en vivo',
        'live_video_widget_url' => 'URL de video en vivo',
    ],
    'Redes y Facebook' => [
        'facebook_page_url' => 'URL de Facebook',
        'facebook_page_id' => 'ID de pagina Facebook',
        'facebook_page_plugin_url' => 'URL plugin de Facebook',
    ],
    'Contacto' => [
        'WHATSAPP_CABINA' => 'WhatsApp de cabina',
        'WHATSAPP_PUBLICIDAD' => 'WhatsApp de publicidad',
        'RADIO_EMAIL' => 'Correo institucional',
    ],
];
?>
<header class="admin-topbar">
    <div><span>Configuracion editable</span><h1>Configuracion general</h1></div>
    <div class="admin-profile"><a href="/admin/dashboard/">Dashboard</a></div>
</header>
<?php if ($flashSuccess !== null): ?><div class="admin-notice"><?= $e($flashSuccess) ?></div><?php endif; ?>
<?php if ($error !== null): ?><div class="admin-error"><?= $e($error) ?></div><?php endif; ?>
<?php if (isset($errors['_form'])): ?><div class="admin-error"><?= $e((string) $errors['_form']) ?></div><?php endif; ?>
<section class="admin-panel">
    <p>.env sigue funcionando como fallback. Los cambios guardados aqui afectan la web publica.</p>
    <?php if (!$canEdit): ?><p><strong>Vista de solo lectura:</strong> solo Super Admin puede modificar configuraciones criticas.</p><?php endif; ?>
    <form class="admin-form" action="/admin/settings/" method="post">
        <input type="hidden" name="_csrf_token" value="<?= $e(SecurityHelper::csrfToken()) ?>">
        <?php foreach ($fields as $section => $sectionFields): ?>
            <h2><?= $e($section) ?></h2>
            <?php foreach ($sectionFields as $key => $label): ?>
                <label><?= $e($label) ?>
                    <input type="text" name="<?= $e($key) ?>" value="<?= $e((string) ($values[$key] ?? '')) ?>" <?= !$canEdit ? 'readonly' : '' ?>>
                    <?php if (isset($errors[$key])): ?><small><?= $e((string) $errors[$key]) ?></small><?php endif; ?>
                </label>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <h2>Texto institucional</h2>
        <label>Texto breve
            <textarea name="RADIO_ABOUT" rows="4" <?= !$canEdit ? 'readonly' : '' ?>><?= $e((string) ($values['RADIO_ABOUT'] ?? '')) ?></textarea>
            <?php if (isset($errors['RADIO_ABOUT'])): ?><small><?= $e((string) $errors['RADIO_ABOUT']) ?></small><?php endif; ?>
        </label>
        <button class="btn btn-primary" type="submit" <?= !$canEdit ? 'disabled' : '' ?>>Guardar configuracion</button>
    </form>
</section>
