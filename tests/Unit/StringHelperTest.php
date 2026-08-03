<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\StringHelper;
use PHPUnit\Framework\TestCase;

final class StringHelperTest extends TestCase
{
    public function testGeneratesSimpleSlug(): void
    {
        self::assertSame('radio-rumbo-huancayo', StringHelper::slug('Radio Rumbo Huancayo'));
    }

    public function testGeneratesSlugWithSpecialCharacters(): void
    {
        self::assertSame('noticias-en-vivo', StringHelper::slug('Noticias en vivo!'));
    }

    public function testReturnsFallbackForEmptySlug(): void
    {
        self::assertSame('sin-titulo', StringHelper::slug('---'));
    }
}
