<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validators\ProgramValidator;
use PHPUnit\Framework\TestCase;

final class ProgramValidatorTest extends TestCase
{
    public function testAcceptsValidProgram(): void
    {
        $errors = (new ProgramValidator())->validate([
            'name' => 'Programa matinal',
            'day_of_week' => 'monday',
            'start_time' => '06:00',
            'end_time' => '09:00',
        ]);

        self::assertSame([], $errors);
    }

    public function testRejectsInvalidDay(): void
    {
        $errors = (new ProgramValidator())->validate([
            'name' => 'Programa matinal',
            'day_of_week' => 'feriado',
            'start_time' => '06:00',
            'end_time' => '09:00',
        ]);

        self::assertArrayHasKey('day_of_week', $errors);
    }

    public function testRejectsInvalidTimeRange(): void
    {
        $errors = (new ProgramValidator())->validate([
            'name' => 'Programa matinal',
            'day_of_week' => 'monday',
            'start_time' => '09:00',
            'end_time' => '06:00',
        ]);

        self::assertArrayHasKey('end_time', $errors);
    }
}
