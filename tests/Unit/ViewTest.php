<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\View;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ViewTest extends TestCase
{
    public function testRejectsInvalidViewPath(): void
    {
        $this->expectException(InvalidArgumentException::class);

        View::render('../config/app');
    }

    public function testHandlesMissingViewWithoutExposingPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La vista solicitada no existe.');

        View::render('public.inexistente');
    }
}
