<?php

declare(strict_types=1);

$urgentNotices = is_array($mock['urgent_notices'] ?? null) ? $mock['urgent_notices'] : [];
?>
<?php if ($urgentNotices !== []): ?>
    <section class="urgent-notice-bar" aria-label="Avisos importantes">
        <div class="container">
            <?php foreach (array_slice($urgentNotices, 0, 2) as $notice): ?>
                <article class="urgent-notice urgent-notice-<?= $e((string) $notice['type']) ?>">
                    <strong><?= $e((string) $notice['title']) ?>:</strong>
                    <span><?= $e((string) $notice['message']) ?></span>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
