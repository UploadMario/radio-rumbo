<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validators\SettingValidator;
use PHPUnit\Framework\TestCase;

final class SettingValidatorTest extends TestCase
{
    public function testAcceptsValidSettings(): void
    {
        $errors = (new SettingValidator())->validate([
            'RADIO_NAME' => 'Radio Rumbo',
            'RADIO_TAGLINE' => 'La radio que se ve',
            'RADIO_LOCATION' => 'Huancayo, Peru',
            'stream_audio_url' => 'https://example.com/audio',
            'RADIO_EMAIL' => 'radio@example.test',
        ]);

        self::assertSame([], $errors);
    }

    public function testRejectsUnsafeUrl(): void
    {
        $errors = (new SettingValidator())->validate([
            'RADIO_NAME' => 'Radio Rumbo',
            'RADIO_TAGLINE' => 'La radio que se ve',
            'RADIO_LOCATION' => 'Huancayo, Peru',
            'facebook_page_url' => 'javascript:alert(1)',
        ]);

        self::assertArrayHasKey('facebook_page_url', $errors);
    }
}
