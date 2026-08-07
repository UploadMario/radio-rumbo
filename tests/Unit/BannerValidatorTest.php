<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validators\BannerValidator;
use PHPUnit\Framework\TestCase;

final class BannerValidatorTest extends TestCase
{
    public function testAcceptsValidBanner(): void
    {
        $errors = (new BannerValidator())->validate([
            'title' => 'Publicidad local',
            'position' => 'home_middle',
            'status' => 'active',
            'link_url' => 'https://example.com',
            'start_at' => '2026-08-04 08:00',
            'end_at' => '2026-08-04 09:00',
        ]);

        self::assertSame([], $errors);
    }

    public function testRejectsInvalidStatusAndDateRange(): void
    {
        $errors = (new BannerValidator())->validate([
            'title' => 'Publicidad local',
            'position' => 'home_middle',
            'status' => 'visible',
            'start_at' => '2026-08-04 10:00',
            'end_at' => '2026-08-04 09:00',
        ]);

        self::assertArrayHasKey('status', $errors);
        self::assertArrayHasKey('end_at', $errors);
    }
}
