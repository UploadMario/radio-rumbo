<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\SecurityHelper;
use PHPUnit\Framework\TestCase;

final class LiveVideoPlayerViewTest extends TestCase
{
    public function testRendersEagerSecureIframeForHttpsVideoUrl(): void
    {
        $e = static fn (string $value): string => SecurityHelper::e($value);
        $videoUrl = 'https://servilive.com/live-stream-video-widget/tvrumbo';

        ob_start();
        require dirname(__DIR__, 2) . '/resources/views/partials/live-video-player.php';
        $output = (string) ob_get_clean();

        self::assertStringContainsString('loading="eager"', $output);
        self::assertStringContainsString('allowfullscreen', $output);
        self::assertStringContainsString($videoUrl, $output);
    }

    public function testRendersFallbackWhenVideoUrlIsEmpty(): void
    {
        $e = static fn (string $value): string => SecurityHelper::e($value);
        $videoUrl = '';

        ob_start();
        require dirname(__DIR__, 2) . '/resources/views/partials/live-video-player.php';
        $output = (string) ob_get_clean();

        self::assertStringContainsString('La transmisión de video no está disponible por el momento.', $output);
        self::assertStringNotContainsString('<iframe', $output);
    }
}
