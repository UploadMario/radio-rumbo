<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validators\ContactMessageValidator;
use PHPUnit\Framework\TestCase;

final class ContactMessageValidatorTest extends TestCase
{
    public function testAcceptsValidContactMessage(): void
    {
        $errors = (new ContactMessageValidator())->validate([
            'name' => 'Visitante',
            'email' => 'visitante@example.test',
            'subject' => 'Publicidad local',
            'message' => 'Quiero informacion.',
            'type' => 'advertising',
        ]);

        self::assertSame([], $errors);
    }

    public function testRejectsInvalidMessageType(): void
    {
        $errors = (new ContactMessageValidator())->validate([
            'name' => 'Visitante',
            'subject' => 'Consulta',
            'message' => 'Mensaje',
            'type' => 'otro',
        ]);

        self::assertArrayHasKey('type', $errors);
    }

    public function testDetectsHoneypot(): void
    {
        self::assertTrue((new ContactMessageValidator())->hasSpamTrap(['website' => 'bot']));
    }
}
