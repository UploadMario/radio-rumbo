<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validators\NewsValidator;
use PHPUnit\Framework\TestCase;

final class NewsValidatorTest extends TestCase
{
    public function testRejectsNewsWithoutRequiredFields(): void
    {
        $errors = (new NewsValidator())->validate([]);

        self::assertArrayHasKey('title', $errors);
        self::assertArrayHasKey('summary', $errors);
        self::assertArrayHasKey('body', $errors);
        self::assertArrayHasKey('category', $errors);
    }

    public function testRejectsInvalidStatus(): void
    {
        $errors = (new NewsValidator())->validate([
            'title' => 'Título',
            'summary' => 'Resumen',
            'body' => 'Contenido',
            'category' => 'Huancayo',
            'status' => 'deleted',
        ]);

        self::assertArrayHasKey('status', $errors);
    }
}
